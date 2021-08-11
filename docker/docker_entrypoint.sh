#!/bin/bash
chmod 755 create_template_ini.sh
./create_template_ini.sh
cd php
php gen_stats.php
cd ../

/sbin/my_init