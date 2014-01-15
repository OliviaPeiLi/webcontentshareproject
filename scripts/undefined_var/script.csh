#!/bin/csh -f

set input = vars_in_views.csv
set tmp = module_view.csv
set output = insert.sql

#cat $input | egrep 'application\/modules'  |   \
#	sed 's:.*application.modules.::' | \
#	sed 's:.*application.views.::' | \
#	sed 's:/views/:/:' |   \
#	sed "s:\.php,:,:" | sed "s:\.php::" | \
cat $input | \
	sed 's:.*application.::' | \
	sed 's:,\$:,:g' > $tmp


cat $tmp | egrep -v "," > ${output}_novar
echo 'INSERT INTO  `fantoon_ci`.`undefined_var` ( `id` , `view_page` , `variable`) VALUES' > ${output}
cat ${output}_novar | xargs -ixx echo "( NULL , 'xx', '')," | sed 's:\r::' >> $output

cat $tmp | egrep "," | cut -d , -f1  | sed "s:^:':" | sed 's:$:'\'':' > ${output}_1
cat $tmp | egrep "," | cut -d , -f2- | sed "s:':'':g" | sed "s:^:':" > ${output}_2

paste ${output}_1 ${output}_2 | sed 's:\t: , :' | sed 's:^:( NULL , :' | sed 's:$:'\''),:' | sed 's:\r::' >> ${output}
vi -c 'normal G$xA;' -c ":wq" ${output}

rm $tmp ${output}_*
#| sed 's:$:'\''),:' >> ${output}_3
#dos2unix -o ${output}_3 -n ${output}
#cat $var xargs -ixx echo "( NULL , xx )," >> $output


#( NULL ,  'topics/topic_more_interests',  'pages,value[''page_id''],value[''uri_name''],value[''thumbnail''],value[''interest_id''],value[''page_name''],loaded_pages');
