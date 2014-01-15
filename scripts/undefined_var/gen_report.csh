#!/bin/csh -f

set folder = "~/public_html/application"
set script = "~/bin/varinviews.pl"

cd $folder
find -type f | egrep 'views\/' | egrep -v '\.js$' | xargs -ixx readlink -f xx | xargs -ixx $script xx 

