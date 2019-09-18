#!/usr/bin/php
for file in /home/eBCC-csv-TPH/*.csv; do
	echo ${file##*/}
	wget -q --no-check-certificate -O- "http://tap-motion.tap-agri.com/ebcc/qrcode/sync_tph.php?file=${file##*/}"
done
