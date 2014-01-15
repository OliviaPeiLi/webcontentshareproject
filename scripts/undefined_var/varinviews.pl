#! /usr/bin/env perl

$\ = "\r\n";

$fn = $ARGV[0];
#$signal = '\$[a-zA-Z\-\>0-9_\[\]\'"]*';
$signal = '\$(([\$a-zA-Z0-9_\[\]\'"]*)(->)?)*';
$space = '[\s\t]*';

if (not -e $fn) {
    print "file ($fn) is not available";
    exit;
}

printf $fn;

@token = ();
@local = ();	#variable is declared inside views (from transfer from controller to views)
@localq = ();	#variable is declared inside views (from transfer from controller to views)

if (not open(viewfile, "<$fn")) { die("can not open $fn"); }

$comment = 0;

while(<viewfile>) {
    my($line) = $_;
    chomp($line);

	#process comment lines
    $line =~ s/\/\/.*//g;
    $line =~ s/<!--.*-->//g;
    $line =~ s/\/\*.*\*\///g;

    if ($line =~ /\/\*.*/) {
    	$comment = $comment + 1;
    	$line =~ s/\/\*.*//;
    	#print '(hit /*)';
    }

    if ($line =~ /<!--/) {
    	$comment = $comment + 1;
    	$line =~ s/<!--.*//;
    	#print '(hit <!--)';
    }

	#process uncomment
    if ($comment > 0) {
    	if ($line !~ /\*\// && $line !~ /-->/) {
    		next;
    	} else {
    		#print 'hit */ or -->';
    		$comment = $comment - 1;
    		if ($comment > 0) {
    			next;
    		} else {
    			$line =~ s/.*-->//;
    			$line =~ s/.*\*\///;
    		}
    	}
    }

    # searching for variable token
    do {
        if ($line =~ /($signal)/) {
        	$word = $1;

			#remove var inside a string (ex: "$var")
        	$word =~ s/['"]$//;

			#avoid $this->, $ <- jquery, and exist token
        	if ($word !~ /(this->)/ && $word ne "\$" &&
        		$word !~ /\$_/ &&
				#token is exist
        		grep($word eq $_, @token )<1 &&
				#token matches with local variable (ex: $token = $localvar->....)
        		grep($word =~ /($_)(->)/, @localq)<1  &&
        		grep($word =~ /($_)(\[)/, @localq)<1  &&
				#token is local
        		grep($word eq $_, @local)<1 ) {

        		if ($word !~ />[0-9]/) {  #ignore expression such as $count>1
        			#print "($line) -- ($word)";
        			$varname = $word;
        			$varname =~ s/\$//;
        			$varname = quotemeta($varname);

        			if ( $line =~ /isset($space)\(($space)\$($varname)($space)\)/ ) {
						# isset($var)
        				push(@local, $word);
        				push(@localq, quotemeta($word));

        				$line =~ s/isset($space)\(($space)\$($varname)($space)\)//;
        			} elsif ( $line =~ /\$($varname)($space)=([^=>])/ ) {
						#avoid local assignment
						# ok   $varname =
						# ng   $varname ==          (ex: compare)
						# ng   $varname => $...     (ex: foreach)
	        			#if ( $line =~ /\$$varname[\t\s]*=[^=>]/ ) {
	        			#if ( $line =~ /\$($varname)($space)(*=)([^=>])/ ) {
        				push(@local, $word);
        				push(@localq, quotemeta($word));
        				#print "local=$word";
        			#} elsif ( $line =~ /as[\s\t]*\$$varname[\t\s]*=>/ ) {
        			} elsif ( $line =~ /as($space)\$($varname)($space)=>($space)($signal)/ ) {
						# foreach ( .... as $key => $val )
        				push(@local, $word);
        				push(@localq, quotemeta($word));

						# not only add $key but also $val
        				$word = $5;
        				push(@local, $word);
        				push(@localq, quotemeta($word));

        				#$line =~ s/\$$varname[\t\s]*=>[\t\s]*\$//;
        				$line =~ s/\$$varname($space)=>($space)\$//;
        			} elsif ( $line =~ /as($space)\$($varname)($space)\)/ ) {
						#foreach ($vars as $var)
        				push(@local, $word);
        				push(@localq, quotemeta($word));
        				$line =~ s/\$$varname($space)\)//;
        			} else {
        				#add to array
        				push(@token, $word);
            			printf ",$word";
            		}
            	}
            }
            #remove
            $line =~ s/($signal)//;
        }
    } while ($line =~ /($signal)/);
}

close(viewfile);

print;

