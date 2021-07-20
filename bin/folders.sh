#! /bin/bash
# Script creates folders and sets rights for them

folders=("temp" "log" "www/files/ckfiles" "www/files/image" "admin/var")

for folder in ${folders[*]}
do
    echo -e "--- $folder ---" 
    mkdir -p $folder # create folder if not exists
    chmod -v 777 $(find $folder -type d) # set rights to folder and its subfolders
done
