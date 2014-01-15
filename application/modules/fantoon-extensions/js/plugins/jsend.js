/* -----------------------------------------------------------------------------
 * jSEND      v2.0.0
 * -----------------------------------------------------------------------------
 * Date:      Fri Sep 24 19:35:11 2010 +0100 
 *  
 * Summary:   This plugin provides compression & binary-to-text encoding
 *            for use in XMLHTTPRequest/AJAX post requests
 *   
 * Author:    Michael Kortstiege, Copyright 2010
 * Website:   http://jsend.org/ 
 *  
 * License:   Dual licensed under the MIT or GPL Version 2 licenses.
 *            (http://jsend.org/license/)
 *
 * Credits:   See http://jsend.org/about/
 *   
 * -----------------------------------------------------------------------------
 * USAGE
 * -----------------------------------------------------------------------------
 * X/HTML     <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js"><script>
 *            <script type="text/javascript" src="jsend.min.js"></script>
 *   
 * JS         var str = "String to Squeeze, Encode & Deliver"; 
 *            var data = $.jSEND(str);
 *            // Send data to server
 * -----------------------------------------------------------------------------    
 */
//RR - USed by the bookmarklet to compress html data for faster transfer
define(['jquery'], function($)
{
  $.jSEND = function(sData, callback)
  {
    /* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
       MAIN SQUEEZE
    ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
    sData = String.fromCharCode(74)+sData;
    /* --------
       Init
    -------- */ 
    var maxInterval = 2000;
    var iCount = 256;
    var sFill = '';
    var iFillCount = 0;
    var iEmptyCount = 0;
    for (var i = 0; i < iCount; i++)
      sFill += String.fromCharCode(224);
  
    var iDictSize = iCount;
    var oDictionary = {};
    for (var i = 0; i < iCount; i++)
      oDictionary[String.fromCharCode(i)] = i;
  
    var aCodes = [];
    var sData2 = ''; //String.fromCharCode(224);
    var sPattern = '';
    /* --------
       Go
    -------- */ 
	//console.info('Compress');
	doJob(sData, 0, function() {
		flush();
	});
      
    /* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
       FUNCTIONS
    ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
    function flush() {
		//console.info('Flush');
    	 /* ----------
	        Flush
	     ---------- */ 
	     if (sPattern != '')
	         aCodes.push(oDictionary[sPattern]);
	     if (iFillCount > 0)
	       sData2 += sFill.substr(0,iFillCount);
	       
	     /* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	        SUB-SQUEEZE & DOUBLE ENCODING
	     ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
	     //console.info('encode1');
	     var sChars = ecode847(encodeBinary(aCodes));
	     if (sData.length != iEmptyCount) {
		   //console.info('encode2');
		   compressLZW(sData2, 0, function(sChars2) {
			   //console.info('encode3');
			   sChars2 = encodeBinary(sChars2);
			   //console.info('encode4');
		       sChars2 = ecode847(sChars2);
		       //console.info('Save');
		       callback.call(this, sChars + '==' + sChars2);
		   });
	     } else {
	    	 callback.call(this, sChars); 
	     }
    }	
    	
    function doJob(sData, i, callback) {
    	var len = Math.min(i+maxInterval, sData.length);
    	//console.info('packet ', i);
    	for (i; i < len; i++) {
	    	var sChar = sData.charAt(i);
	        var iCode = sChar.charCodeAt(0);
	        /* --------------------
	           Handle UCS Chars
	        -------------------- */ 
	        if (iCode > 255) {
	          /* ------------------------------------------------------------
	             Replace some UCS Chars with their ANSI (128-159) pendants
	          ------------------------------------------------------------ */ 
	          var iChk = iCode;
	          switch (iCode) {
	              case 8364: iCode = 128; break; case 8218: iCode = 130; break; case 402:  iCode = 131; break;
	              case 8222: iCode = 132; break; case 8230: iCode = 133; break; case 8224: iCode = 134; break;
	              case 8225: iCode = 135; break; case 710:  iCode = 136; break; case 8240: iCode = 137; break;
	              case 352:  iCode = 138; break; case 8249: iCode = 139; break; case 338:  iCode = 140; break;
	              case 381:  iCode = 142; break; case 8216: iCode = 145; break; case 8217: iCode = 146; break;
	              case 8220: iCode = 147; break; case 8221: iCode = 148; break; case 8226: iCode = 149; break;
	              case 8211: iCode = 150; break; case 8212: iCode = 151; break; case 732:  iCode = 152; break;
	              case 8482: iCode = 153; break; case 353:  iCode = 154; break; case 8250: iCode = 155; break;
	              case 339:  iCode = 156; break; case 382:  iCode = 158; break; case 376:  iCode = 159; break;
	            }
	            if (iChk != iCode) {
	              sChar = String.fromCharCode(iCode);
	              iEmptyCount++;
	              iFillCount++;
	              if (iFillCount >= iCount) {
	                sData2 += sFill;
	                iFillCount = 0;
	              }
	            }
	            else {
	              if (iFillCount > 0) { 
	                sData2 += sFill.substr(0,iFillCount);
	                iFillCount = 0;
	              }
	              sData2 += String.fromCharCode(parseInt(iCode / 256));
	            }
	        }
	        /* ---------------------------
	           Handle ASCII/ANSI Chars
	        --------------------------- */ 
	        else {
	          iEmptyCount++;
	          iFillCount++;
	          if (iFillCount >= iCount) {
	            sData2 += sFill;
	            iFillCount = 0;
	          }
	        } 
	        /* -------------------------
	           Start LZW Compression
	        ------------------------- */    
	        var sCombined = sPattern + sChar;  
	        if (oDictionary[sCombined]) {
	            sPattern = sCombined;  
	        } else {   
	          	if (iCode > 255) sChar = String.fromCharCode(iCode % 256); 
	            aCodes.push(oDictionary[sPattern]);
	            oDictionary[sCombined] = iDictSize++;
	            sPattern = '' + sChar;
	        }  
	         /* -----------------------
	           End LZW Compression
	        ----------------------- */
    	}
    	
        if (i < sData.length) {
        	window.setTimeout(function() {
            	doJob(sData, i, callback);
            },1);
        } else {
        	callback.call(this);
        }
    }
    
    /* ------------------
       LZW Compressor
    ------------------ */ 
    function compressLZW(sData, i, callback, aCodes, iDictSize, oDictionary, sPattern) {
    	aCodes = aCodes ? aCodes : [];
		iDictSize = iDictSize ? iDictSize : 256; 
		sPattern = sPattern ? sPattern : '';
      var iLn = Math.min(sData.length, i+maxInterval);
      
      if (!oDictionary) {
    	  oDictionary = {};
    	  for (var j = 0; j < 256; j++) oDictionary[String.fromCharCode(j)] = j;
      }
      
      for (i; i < iLn; i++) 
      {
      	var sChar = sData.charAt(i); 
        var sCombined = sPattern + sChar;  
        if (oDictionary[sCombined])
            sPattern = sCombined;  
        else 
        {   
            aCodes.push(oDictionary[sPattern]);
            oDictionary[sCombined] = iDictSize++;
            sPattern = '' + sChar;
        }  
      }
      
      if (i < sData.length) {
    	  window.setTimeout(function() {
    		  compressLZW(sData, i, callback, aCodes, iDictSize, oDictionary, sPattern)
    	  }, 1);
      } else {
          if (sPattern != '') aCodes.push(oDictionary[sPattern]);
    	  callback.call(this, aCodes);
      }
    }
    /* ------------------
       Binary Encoder
    ------------------ */ 
    function encodeBinary(aCodes) 
    {
      var iDictCount = 256;
      var aCharCodes = [];
      var iBits = 8;
      var iRest = 0;
      var iRestLength = 0;
      for(var i=0, iLn = aCodes.length; i < iLn; i++) 
      {
        iRest = (iRest << iBits) + aCodes[i];
        iRestLength += iBits;
        iDictCount++;
        if (iDictCount >> iBits) 
            iBits++;
        while (iRestLength > 7) 
        {
          iRestLength -= 8;
          aCharCodes.push(iRest >> iRestLength);
          iRest &= (1 << iRestLength) - 1;
        }
      }
      aCharCodes.push(iRestLength ? iRest << (8 - iRestLength) : '');
      return aCharCodes;
    }
     /* ------------------
       847enc Encoder
    ------------------ */ 
    function ecode847(aCharCodes) 
    {
      var aTmp = [];
      var iCount = 0;
      var iChar = 0;
      var sChars = '';
      for(var i=0, iLn = aCharCodes.length; i < iLn; i++) 
      {
        var iValue = aCharCodes[i];
        if (iValue > 127) {
          iValue -= 128;
          iChar += Math.pow(2,iCount);
        }
        if (iValue == 0  || iValue == 34 || iValue == 37 || iValue == 38 || 
            iValue == 39 || iValue == 43 || iValue == 61 || iValue == 92)
          aTmp.push('='+String.fromCharCode((iValue+16)));
        else
          aTmp.push(String.fromCharCode(iValue));
        iCount++;
        if (iCount > 6) 
        {
          if (iChar == 0  || iChar == 34 || iChar == 37 || iChar == 38 || 
              iChar == 39 || iChar == 43 || iChar == 61 || iChar == 92)
            sChars += ('=' + String.fromCharCode((iChar+16)) + aTmp.join(''));
          else
            sChars += (String.fromCharCode(iChar) + aTmp.join(''));
          aTmp = [];
          iChar = 0;
          iCount = 0;
        }
      }
      if (iChar == 0  || iChar == 34 || iChar == 37 || iChar == 38 || 
          iChar == 39 || iChar == 43 || iChar == 61 || iChar == 92)
        sChars += ('=' + String.fromCharCode((iChar+16)) + aTmp.join(''));
      else
        sChars += (String.fromCharCode(iChar) + aTmp.join(''));
      return sChars;
    }
  }
});