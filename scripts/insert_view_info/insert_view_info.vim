:exec "normal gg0i<?php if ($this->is_mod_enabled('view_debug')) { echo '<!-- begin of (" expand("%:p") ") -->'; } ?>" "\<CR>"
:normal gg
:s:/home/quangphan/public_html/::

:exec "normal GA""\<CR><?php if ($this->is_mod_enabled('view_debug')) { echo '<!-- end of (" expand("%:p") ") -->'; } ?>"
:s:/home/quangphan/public_html/::

:wq!
