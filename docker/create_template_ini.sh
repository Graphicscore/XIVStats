#!/bin/bash
#Create a tempalteconfig.ini with the content passed as command line arguments
echo "host = $DB_HOST" > php/templateconfig.ini
echo "database = $DB_DATABASE" >> php/templateconfig.ini
echo "username = $DB_USER" >> php/templateconfig.ini
echo "password = $DB_PASSWORD" >> php/templateconfig.ini