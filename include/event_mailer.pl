#! /usr/bin/perl -w
#############################################################################
#                                                                           #
# Copyright (C) 2001-2002, Wolfgang Karall <spiney@spiney.org>              #
# All rights reserved.                                                      #
#                                                                           #
#############################################################################
# This program is free software; you can redistribute it and/or             #
# modify it under the terms of the GNU General Public License               #
# as published by the Free Software Foundation; either version 2            #
# of the License, or (at your option) any later version.                    #
#                                                                           #
# This program is distributed in the hope that it will be useful,           #
# but WITHOUT ANY WARRANTY; without even the implied warranty of            #
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the             #
# GNU General Public License for more details.                              #
#                                                                           #
# You should have received a copy of the GNU General Public License         #
# along with this program; if not, write to the Free Software Foundation,   #
# Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.           #
#############################################################################

use strict;

package main;

use XML::Parser;
use Time::ParseDate;
use Time::CTime;
use Mail::Mailer;
use Unicode::String qw(utf8 latin1);

#############################################################################
# Change the following entries according to the README file                 #
#############################################################################

my $from_address 	= 'spiney@spiney.org';
my $install_dir 	= '/home/spiney/public_html/Work/phpRecEvent/www/';
my $work_dir 		= '/home/spiney/.event_mailer/';

#############################################################################
#    DON'T CHANGE ANYTHING BELOW HERE UNLESS YOU KNOW WHAT YOU'RE DOING!    #
#############################################################################

# some vars

my $debug = 0;
my $base_url;
my $events_dir;
my $template_dir;
my $defaults_dir;
my $data_dir;
my $event_file;
my $participants_file;
my $include_file;
my $mail_template;
my $success_template;
my $failure_template;
my $person_xml_template;
my $participants_xml_template;

my $times_before;
my $times;
my $now = time();
my %event_data;
my $period_count;
my %participants_data;


# check the install directory

-d $install_dir || die "$install_dir is not a directory!\n";
if ( $install_dir !~ /\/$/ ) { $install_dir .= "/"; } # make sure it ends in /

# check the work directory

if ( ! -e $work_dir )
{
	mkdir($work_dir, 0700);
}
else
{
	-d $work_dir || die "$work_dir is not a directory!\n";
}
if ( $work_dir !~ /\/$/ ) { $work_dir .= "/"; } # make sure it ends in /



# the two needed parsers

my $event_parser = 
	new XML::Parser(Handlers 		=> { 
					Start => \&event_start_element,
					Char  => \&event_char_data, 
					},
			ProtocolEncoding 	=> 'ISO-8859-1');

my $participants_parser = 
	new XML::Parser(Handlers 		=> { 
					Start => \&participants_start_element
					},
			ProtocolEncoding 	=> 'ISO-8859-1');

# get some variables from defaults.inc

open (DEF_FILE, $install_dir . "defaults.inc") || 
	die "Can't open default.inc: $!\n";

while (<DEF_FILE>)
{
	$events_dir = $1 
		if /"EVENTS_DIR",.*"([^"]+)"/;
	$template_dir = $1 
		if /"TEMPLATE_DIR",.*"([^"]+)"/;
	$defaults_dir = $1 
		if /"DEFAULTS_DIR",.*"([^"]+)"/;
	$data_dir = $1 
		if /"DATA_DIR",.*"([^"]+)"/;

	$base_url = $1 
		if /"BASE_URL",.*"([^"]+)"/;

	$times_before = $1 
		if /"TIMES_BEFORE", *([^ ]+)\)/;
	$times = $1 
		if /"TIMES", *([^ ]+)\)/;
	
	$event_file = $data_dir . $1 
		if /"EVENT_FILE",.*"([^"]+)"/;
	$participants_file = $data_dir . $1 
		if /"PARTICIPANTS_FILE",.*"([^"]+)"/;
	$include_file = $1
		if /"INCLUDE_FILE",.*"([^"]+)"/;
	$mail_template = $template_dir . $1 
		if /"MAIL_TEMPLATE",.*"([^"]+)"/;
	$success_template = $template_dir . $1 
		if /"MAIL_SUCCESS_TEMPLATE",.*"([^"]+)"/;
	$failure_template = $template_dir . $1 
		if /"MAIL_FAILURE_TEMPLATE",.*"([^"]+)"/;
	$person_xml_template = $defaults_dir . $1 
		if /"PERSON_XML_TEMPLATE",.*"([^"]+)"/;
	$participants_xml_template = $defaults_dir . $1 
		if /"PARTICIPANTS_XML_TEMPLATE",.*"([^"]+)"/;

}

close DEF_FILE;

# check if the found events_dir

if ( ! defined($events_dir) )
{
	die "Can't find the events directory!\n";
}
else
{
	$events_dir = $install_dir . $events_dir;
}

# get all the directories from the events_dir

opendir(DIR, $events_dir) || die "Can't opendir $events_dir: $!\n";
my @events = grep { /^[^\.]/ && -d "$events_dir/$_" } readdir(DIR);
closedir(DIR);

if ( $#events + 1 == 0 )
{
	die "Can't find any events in $events_dir!\n";
}

# main loop over all the events

foreach my $event (@events)
{
	# reset the some variables

	undef %event_data;
	undef %participants_data;
	$period_count = 0;

	# some local vars

	my $enrolled;
	my $mails_sent = 0;
	my $reset = 0;
	my $date_format;
	my $announce_subject;
	my $report_subject;
	undef my @template;
	undef my @done_addresses;

	
	if ( $event =~ /^CVS$/ ) # skipping silly CVS entry
	{
		next;
	}

	# read the work data
	
	if ( open(WORK_DATA, $work_dir . $event) )
	{
		@done_addresses = <WORK_DATA>;
		close(WORK_DATA);
	}

	# get some variables from event.inc

	open (INCLUDE_FILE, $events_dir . $event .
		"/" . $include_file) || 
		die "Can't open include file: $!\n";

	while (<INCLUDE_FILE>)
	{
		$date_format = $1
			if /"CRON_DATE_FORMAT",.*"([^"]+)"/;
		$announce_subject = $1 
			if /"CRON_ANNOUNCE_SUBJECT",.*"([^"]+)"/;
		$report_subject = $1
			if /"CRON_REPORT_SUBJECT",.*"([^"]+)"/;
	}

	close INCLUDE_FILE;
	
	$announce_subject =~ s/<!--EVENTNAME-->/$event/;
	$report_subject =~ s/<!--EVENTNAME-->/$event/;


	d_print(1, "Parsing event: " . $event . "\n");
	
	# assembling the URL of the login page we'll tell people in the mail

	my $url = $base_url . "login.php?event=" . $event;

	# parse the XML files

	$event_parser->parsefile($events_dir . 
					$event . 
					"/" . 
					$event_file);

	$participants_parser->parsefile($events_dir . 
					$event . 
					"/" . 
					$participants_file);

	# calculate the next_event and next_deadline values

	my $next_event = 
		next_event_date($period_count, 
				$event_data{'weekday'},
				$event_data{'start_hour'},
				$event_data{'start_minute'}
				);

	# skip events with no next_event date
	
	if  ( $next_event == -1 )
	{
		warn "No next event date for event $event!\n";
		next;
	}
	
	
	my $next_deadline = 
		$event_data{'weekday'} eq $event_data{'deadline'} ?
			parsedate(strftime("%Y-%m-%d", localtime($next_event))
				. " " .  $event_data{'deadline_hour'} . ":" .
				$event_data{'deadline_minute'},
				NOW => $now) :
			parsedate("last " . $event_data{'deadline'} 
				. " " .  $event_data{'deadline_hour'} . ":" .
				$event_data{'deadline_minute'},
				NOW => $next_event);
	my $next_event_txt = 
		strftime($date_format, localtime($next_event));
	my $next_deadline_txt = 
		strftime($date_format, localtime($next_deadline));
			

	# some things for debugging only

	d_print(1, "==========\n");
	d_print(1, "Event data\n");
	d_print(1, "==========\n");

	if ( $debug >= 2 )
	{
		while ((my $key, my $value) = each(%event_data) )
		{
			if ( $key ne "period" )
			{
				d_print(2, "$key - $value\n");
			}
		}
	}



	d_print(1, "===========\n");
	d_print(1, "Period data\n");
	d_print(1, "===========\n");

	if ( $debug >= 2 )
	{
		for (my $i=1;$i<=$period_count;$i++)
		{
			d_print(2, "ID:    " . 
				$event_data{'period'}[$i]->{'id'} 
				. "\n");
			d_print(2,  "Start: " . 
				localtime($event_data{'period'}[$i]->{'start'})
				. "\n");
			d_print(2,  "End:   " . 
				localtime($event_data{'period'}[$i]->{'end'}) 
				. "\n");
		}
	}


	# do the actual work

	d_print(1, "=================\n");
	d_print(1, "Participants data\n");
	d_print(1, "=================\n");

	# for every of the parsed participants
	
	foreach my $key (sort(keys %participants_data))
	{

		my $participant = $participants_data{$key};

		d_print(2, $participant->{username} . " - ");
		d_print(2, $participant->{fullname} . " - ");
		d_print(2, $participant->{email} . " - ");
		d_print(2, $participant->{priority} . " - ");
		d_print(2, $participant->{group} . "\n");

		$enrolled++ if $participant->{enrolled};

		# if the user hasn't got an email yet and if the time
		# is right according to his priority, we do some mailing
		
		if ( ( grep(/$participant->{email}/, @done_addresses) == 0 )
			and
		     ( $next_event -
		       ( $times_before - $participant->{priority} ) * $times )
		     < $now )
		{
			# fetch the template for this priority

			open(MAIL_TEMPLATE, 
				$events_dir . $event . "/" . 
				$mail_template . 
				"-priority-" . $participant->{priority}) ||
			    warn "Can't open mail template: $!\n";
			    
			@template = <MAIL_TEMPLATE>;

			close(MAIL_TEMPLATE);

			# do the substitutions

			foreach (@template)
			{
				s/<!--URL-->/$url/g;
				s/<!--FULLNAME-->/$participant->{fullname}/g;
				s/<!--TITLE-->/$event_data{'title'}/g;
				s/<!--NEXT-->/$next_event_txt/g;
				s/<!--DEADLINE-->/$next_deadline_txt/g;
			}
			
			# do the mailing
			
			my $mailer = new Mail::Mailer;
			
			$mailer->open({ From	=> $from_address,
			                To	=> $participant->{email},
					Subject => $announce_subject,
					})
					or die "Can't open mailer: $!\n";
					
			print $mailer @template;

			$mailer->close();

			# remember that we've done that recipient
			
			push (@done_addresses, "$participant->{email}\n");

			$mails_sent++;

		}
		
		# if some user is in our @done_addresses list but is not 
		# supposed to yet (e.g. if the last event passed and we're in 
		# a new week) we delete the user from the list

		elsif ( ( grep(/$participant->{email}/, @done_addresses) == 1 ) 
			and
		      ( $next_event - 
			( $times_before - $participant->{priority} ) * $times )
			> $now )
		{
			
			@done_addresses = grep(!/$participant->{email}/, 
						@done_addresses);
			
			$reset++;
		}

	}
	
	# if we're past the deadline, check whether we reached 
	# the mininum of needed participants and then send the
	# proper email to the people
	
	if ( ( $next_deadline <= $now ) and
	     ! ( -f $work_dir . $event . ".done" ) )
	{

		if ( $enrolled >= $event_data{'min_enrolled'})
		{
			
			open(MAIL_TEMPLATE, 
				$events_dir . $event . 
				"/" .  $success_template) ||
			    warn "Can't open mail template: $!\n";
			    
			@template = <MAIL_TEMPLATE>;

			close(MAIL_TEMPLATE);

		}
		else
		{
			open(MAIL_TEMPLATE, 
				$events_dir . $event . 
				"/" .  $failure_template) ||
			    warn "Can't open mail template: $!\n";
			    
			@template = <MAIL_TEMPLATE>;

			close(MAIL_TEMPLATE);

		}

		foreach my $key (sort(keys %participants_data))
		{

			my $participant = $participants_data{$key};

			my @body = @template;

			# do the substitutions

			foreach (@body)
			{
				s/<!--FULLNAME-->/$participant->{fullname}/g;
				s/<!--TITLE-->/$event_data{'title'}/g;
				s/<!--NEXT-->/$next_event_txt/g;
			}
			
			# do the mailing
			
			my $mailer = new Mail::Mailer;
			$mailer->open({ From	=> $from_address,
			                To	=> $participant->{email},
					Subject => $report_subject,
					})
					or die "Can't open mailer: $!\n";

			print $mailer @body;

			$mailer->close();

		}
		
		# remember that we've done that week

		open(TOUCHED, '>' . $work_dir . $event . '.done');
		close(TOUCHED);

	
	}
	elsif ( ( $next_deadline > $now ) and
	        ( -f $work_dir . $event . ".done" ) )
	{
		unlink($work_dir . $event . ".done");
		foreach my $key (sort(keys %participants_data))
		{
			my $participant = $participants_data{$key};

			$participant->{enrolled} = $participant->{auto_enroll};
		}

		write_participants($event);
	}
	
	if ( $mails_sent > 0 )
	{
		# write the work data
	
		open(WORK_DATA, '>' . $work_dir . $event) || 
			die "Can't open $work_dir$event: $!\n";
		print WORK_DATA @done_addresses;
		close(WORK_DATA);

		d_print(1, "Number of mails sent: $mails_sent\n");
	
	}

	if ( $reset > 0 )
	{
		# write the work data
	
		open(WORK_DATA, '>' . $work_dir . $event) || 
			die "Can't open $work_dir$event: $!\n";
		print WORK_DATA @done_addresses;
		close(WORK_DATA);
	
		d_print(1, "Number of users reset: $reset\n");

	}

	d_print(2, "Calculated Time Values:\n");
	d_print(2, "Next Event: " . 
		strftime('%d.%m.%Y %H:%M:%S', localtime($next_event)) . 
		"\n");
	d_print(2, "Deadline  : " . 
		strftime('%d.%m.%Y %H:%M:%S', localtime($next_deadline)) . 
		"\n\n\n");
}

exit 0;


sub d_print
{
	my $level = shift;

	if ( $debug >= $level )
	{
		print @_;
	}
}

sub write_participants
{
	my $event = shift;
	undef my @person_data;

	d_print(1, "Writing participants for $event\n");
	d_print(2, $person_xml_template . "\n");

	open(PERSON_TEMPLATE, $install_dir . $person_xml_template) ||
		die "Can't open person xml template: $!\n";
	my @person_template = <PERSON_TEMPLATE>;
	close(PERSON_TEMPLATE);

	d_print(2, @person_template);
        
	foreach my $key (sort(keys %participants_data))
	{
		my @person_txt = @person_template;
		my $person = $participants_data{$key};
	
		foreach (@person_txt)
		{
			s/<!--USERNAME-->/$person->{username}/;
			s/<!--FULLNAME-->/$person->{fullname}/;
			s/<!--EMAIL-->/$person->{email}/;
			s/<!--PRIORITY-->/$person->{priority}/;
			s/<!--GROUP-->/$person->{group}/;
			s/<!--ENROLLED-->/$person->{enrolled}/;
			s/<!--AUTOENROLL-->/$person->{auto_enroll}/;
			s/<!--MAILSENT-->/$person->{mail_sent}/;
		}
			                
		@person_data = ( @person_data, @person_txt );
					  
	}
	
	d_print(2, @person_data);

	d_print(2, $participants_xml_template . "\n");
	
	open(PARTICIPANTS_TEMPLATE, $install_dir . $participants_xml_template) ||
		die "Can't open participants xml template: $!\n";
	my @participants_template = <PARTICIPANTS_TEMPLATE>;
	close(PARTICIPANTS_TEMPLATE);
        
	d_print(2, @participants_template);
	
	foreach (@participants_template)
	{
		s/<!--PERSONDATA-->/@person_data/;
	}
			  
	d_print(2, @participants_template);
			    
	open(WRITE_FILE, '>' . $events_dir . $event . "/" . $participants_file);
	print WRITE_FILE @participants_template;
	close(WRITE_FILE);
				            
}
					        




sub next_event_date
{
	my $counter = shift;
	my $weekday = shift;
	my $hour = shift;
	my $minute = shift;

	my $in_period = -1;
	my $next_event = -1;
	undef my %future_periods;
	my $period_starttime;
	
	for (my $i=1;$i<=$counter;$i++)
	{
		if ( ( $event_data{'period'}[$i]->{start} <= $now ) and
		     ( $now <= $event_data{'period'}[$i]->{end} ) )
		{
			$in_period = $i;
			d_print(3, "next_event_date: in period $i\n");
		}
		else
		{
			if ( $event_data{'period'}[$i]->{start} >= $now )
			{
				$future_periods{$i} = 
					$event_data{'period'}[$i]->{start};
				
				d_print(3, "next_event_date: added future " .
					"period: $i, starting " . 
					strftime('%d.%m.%Y %H:%M:%S', 
					  localtime($event_data{'period'}[$i]->{start}))
					. "\n");
			}
		}
	}

	my(@sorted_periods) = 
		sort { $future_periods{$a} cmp $future_periods{$b} } 
			keys %future_periods;


	if ( ( $in_period == -1 ) and ( scalar keys %future_periods == 0 ) )
	{
		d_print(3, "next_event_date: no valid periods found\n");
		return -1;
	}
	else
	{
		if ( $in_period != -1 )
		{
		  $next_event = parsedate("next " . $weekday . " " .
		  			$hour . ":" . $minute, 
						NOW => $now);

		  if ( $now < $next_event - 604800 )
		  {
			$next_event = parsedate("today $hour:$minute");
		  }
			
		  if ( $next_event < $event_data{'period'}[$in_period]->{end} )
		  {
			return $next_event;
		  }
		}
		if ( scalar keys %future_periods != 0 )
		{
		  foreach (@sorted_periods)
		  {
			
			$next_event = parsedate("next " . $weekday . " " .
		  				$hour . ":" . $minute,
			                        NOW => $future_periods{$_});

			d_print(3, "next_event     = $next_event\n");
			d_print(3, "future_periods = $future_periods{$_}\n");

			$period_starttime = parsedate($hour . ":" . $minute, NOW => $future_periods{$_});

			d_print(3, "per._starttime = $period_starttime\n");

			if ( $period_starttime == $next_event - 604800 )
			{
				$next_event = $period_starttime;
			}
			
			if ( $next_event < $event_data{'period'}[$_]->{end} )
			{
				return $next_event;
			}
		  }
		}

		return -1;
	}
}




sub participants_start_element
{
	my $p = shift;
	my $el = shift;

	if ( $el eq "person" )
	{
		my $person = new Person;
		while (@_)
		{
			my $att = utf8(shift)->latin1();
			$person->{$att} = utf8(shift)->latin1();
		}

		$participants_data{$person->{username}} = $person;

	}
}



sub event_start_element
{
	my $p = shift;
	my $el = shift;

	if ( $el eq "time" )
	{
		while (@_)
		{
			my $att = shift;
			if ( $att eq "day" )
			{
				$event_data{'weekday'} = shift;
			}
			elsif ( $att eq "hour" )
			{
				$event_data{'start_hour'} = shift;
			}
			elsif ( $att eq "minute" )
			{
				$event_data{'start_minute'} = shift;
			}
		}
	}

	if ( $el eq "deadline" )
	{
		while (@_)
		{
			my $att = shift;
			if ( $att eq "day" )
			{
				$event_data{'deadline'} = shift;
			}
			elsif ( $att eq "hour" )
			{
				$event_data{'deadline_hour'} = shift;
			}
			elsif ( $att eq "minute" )
			{
				$event_data{'deadline_minute'} = shift;
			}
		}
	}

	if ( $el eq "period" )
	{
		my $period = new Period;
		while (@_)
		{
			my $att = shift;
			$period->{$att} = shift;
		}
		
		$period_count++;

		if ( ! defined($event_data{'period'}) )
		{
			$event_data{'period'} = ();
		}
		
		$event_data{'period'}[$period_count] = $period;

	}
	
}

sub event_char_data
{
	my $p = shift;
	my $data = shift;

	if ( $p->current_element ne "event" )
	{
		$event_data{$p->current_element} = utf8($data)->latin1();
	}
}




package Person;

sub new {
	bless { username    => "",
	        fullname    => "",
		email       => "",
		priority    => -1,
		group       => "",
		enrolled    => 0,
		auto_enroll => 0,
		mail_sent   => 0 }, shift;
}

package Period;

sub new {
	bless { id => -1,
		start => 0,
		end   => 0 }, shift;
}


