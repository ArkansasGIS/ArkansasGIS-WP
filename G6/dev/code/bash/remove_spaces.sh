#!/bin/bash
#
# Writen by Mayuresh Phadke (mayuresh at gmail.com)# To change the names of all files in a directory including directory names
# run the command
#
#  find . -depth -exec ~/rename.sh {} ;
#
# This command is pretty useful if you have a collection of songs or pictures transferred
# from your windows machine and you are finding it difficult to handle the
# spaces in the filenames on UNIX
#
#set -x

progname=`basename $0`

if [ $# != 1 ]
then
        echo "Usage: $progname \"file name with spaces\""
        echo
        echo "This utility is useful for renaming files with spaces in the filename. Spaces in the filename are replaced with _"
        echo "\"file name with spaces\" will be renamed to \"file_name_with_spaces\""
        echo
        exit 1
fi

old_name=$1
dir=`dirname "$1"`
file=`basename "$1"`

new_file=`echo $file|sed "s/ /_/g"`
new_name=$dir"/"$new_file

if [ "$old_name" != "$new_name" ]
then
        mv "$old_name" "$new_name"
fi

exit 0