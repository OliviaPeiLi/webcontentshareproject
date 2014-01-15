<?php
class Sending_email_model extends MY_Model
{
    //relations
    protected $belongs_to = array(
                                'notification' => array(
                                    'foreign_model' => 'notificaiton',
                                    'foreign_column' => 'notification_id'
                                )
                            );

}