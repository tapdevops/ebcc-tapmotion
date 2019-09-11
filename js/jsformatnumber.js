 
////////////////////////////////////////////////////////////////////////////////
////////////////////////////////BEGIN NUMBER FORMATTING/////////////////////////
////////////////////////////////////////////////////////////////////////////////
      
      function formatCurrency(sNumber,groupSeparator,currencySymbol,fractionMark,precision)
      {
            groupSeparator       = groupSeparator || ',';                                                // set default groupSeparator to a comma (,)
            currencySymbol      = currencySymbol  ;                                                // set default currencySymbol to dollars ($)
            fractionMark      = fractionMark || '.';                                                        // set default fractionMark to a period (.)
            precision != 0 ? precision = (precision || 0): null;            // set the default precision to 2 decimal places (extra test allows for 0 to override - exactly what you you would expect)
            
            number = formatNumber(sNumber,groupSeparator,currencySymbol,fractionMark,precision)
            return number;
      }
      
      function formatNumber(sNumber,groupSeparator,currencySymbol,fractionMark,precision)
      {
            groupSeparator       = groupSeparator || ',';                                                // set default groupSeparator to a comma (,)
            currencySymbol      = currencySymbol || '';                                                      // set default currencySymbol to dollars ($)
            fractionMark      = fractionMark || '.';                                                            // set default fractionMark to a period (.)
            precision != 0 ? precision = (precision || 2): null;            // set the default precision to 2 decimal places (extra test allows for 0 to override - exactly what you you would expect)
            
            sUnformattedNumber = unformatNumber(sNumber);                                                                                    
            sRoundedNumber = Math.round(sUnformattedNumber*Math.pow(10,precision))/Math.pow(10,precision)+'';       // round the number AND cast it to a string
            var whole = getWholeNumber(sRoundedNumber);
            var decimal = getDecimalNumber(sRoundedNumber);
            
            //whole = addCommas(Math.abs(whole),groupSeparator); remarked by NB 10.07.2014
            decimal = addZeros(decimal,precision);      
            sFormattedNumber = precision > 0 ?  (currencySymbol + whole + fractionMark + decimal) : (currencySymbol + whole + decimal);      
            if (isNegative(sNumber))
            {
                  sFormattedNumber = '-' + sFormattedNumber;
            }
            return sFormattedNumber;
      
            /*PRIVATE METHODS - formatNumber()*/
                  function addCommas(number,groupSeparator)
                  {
                        var groupSeparator = (groupSeparator || ',');
                        if (number && number !=0)
                        {
                              number +='';
                              if (number.length > 3) 
                              {
                                var mod = number.length % 3;
                            var output = (mod > 0 ? (number.substring(0,mod)) : '');
                            for (i=0 ; i < Math.floor(number.length/3) ; i++) {
              if ((mod ==0) && (i ==0))
              {
                                output+= number.substring(mod+3*i,mod+3*i+3);
              }      
                          else
                                          {
                                                output+= groupSeparator + number.substring(mod+3*i,mod+3*i+3);
                              }
                                    }
                            return (output);
                          }
                              return number+='';
                        }
                        if (number == 0)
                              return number+='';
                        return '';
                  }
                  
                  function addZeros(decimal,precision)
                  {
                        if (precision)
                        {
                              if (decimal.toString().length == 0)
                                    decimal = 0;
                              var zeros='';
                              numberOfZeros = (precision - decimal.toString().length);
                              for (z=0 ; numberOfZeros > z ; z++)
                                    zeros+='0';
                              return decimal + zeros;
                        }
                        return '';
                  }

                  function getDecimalNumber(sNumber)
                  {
                        sNumber = sNumber.toString();
                        if (sNumber.toString().indexOf('.')!= -1)
                        {
                              sWholeNumber = sNumber.substring(sNumber.indexOf('.')+1,sNumber.length);
                        }
                        else
                              sWholeNumber = '';
                        return sWholeNumber;
                  }
                  
                  function getWholeNumber(sNumber)
                  {
                        if (sNumber)
                        {
                              sNumber = sNumber.toString();
                              if (sNumber.toString().indexOf('.')!= -1)
                                    sWholeNumber = sNumber.substring(0,sNumber.indexOf('.'));
                              else
                                    sWholeNumber = sNumber;
                              return sWholeNumber;
                        }
                        return '0';
                  }
            /*PRIVATE METHODS - formatNumber()*/
      }
      
      function isNegative(sNumber)
      {
            return sNumber.toString().indexOf("-") == 0;
      }
      
      function unformatNumber(sNumber,sFractionMark)
      {
            sFractionMark = (sFractionMark || '.');
            sNumber = sNumber.toString();
            if(sNumber || sNumber == 0)
            {
                  var aNumber = sNumber.split(sFractionMark);
                  
                  if (aNumber[1])
                  {
                        var sWholeNumber = removeNonDigits(aNumber[0]);                  
                        var sDecimalNumber = removeNonDigits(aNumber[1]);
                        if (sDecimalNumber == '')
                        {
                              iUnformattedNumber = sWholeNumber - 0;
                              if (sWholeNumber == '') return '';
                        }
                        iUnformattedNumber = sWholeNumber + '.' + sDecimalNumber - 0;
                  }
                  else
                  {
                        var sUnformattedNumber = removeNonDigits(sNumber);
                        if (sUnformattedNumber == '')
                        {
                              return sUnformattedNumber;
                        }
                        var iUnformattedNumber = sUnformattedNumber - 0;
                  }
                  if (isNegative(sNumber))
                        iUnformattedNumber = '-' + iUnformattedNumber - 0;
                  return iUnformattedNumber;
            }
            return sNumber;
    /*PRIVATE METHODS - unformatNumber()*/
          function removeNonDigits(sMixedString)
      {
        var sNumbersOnly = sMixedString.replace(/[^0-9]/g,'');
        return sNumbersOnly;
      }
    /*PRIVATE METHODS - unformatNumber()*/
      }
////////////////////////////////////////////////////////////////////////////////
////////////////////////////////END NUMBER FORMATTING///////////////////////////
////////////////////////////////////////////////////////////////////////////////
