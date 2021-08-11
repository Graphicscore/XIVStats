#!/bin/bash
#Create a tempalteconfig.ini with the content passed as command line arguments
echo "host = $1" > php/templateconfig.ini
echo "database = $2" >> php/templateconfig.ini
echo "username = $3" >> php/templateconfig.ini
echo "password = $4" >> php/templateconfig.ini