#!/bin/bash
#
# copies package files to wbb directory
# parameter 	(1) = directory (e.g. bbcode.google)
#		(2) = destination directory (e.g. /var/www/wbb/wcf)
#
# by Torben Brodt

cd $1

# welcome output
echo ""
echo ">>> ${1} wird synchronisiert >>>>>>>>>>>>>>>>>"
echo ""

# create files.tar
if [ -d "files" ]; then
	cd files
	find * ! \( -name '.*' -prune \) | cpio -pdmu $2
	cd ..
fi


# create templates.tar
if [ -d "templates" ]; then
	cd templates
	find * ! \( -name '.*' -prune \) | cpio -pdmu $2/templates/
	cd ..
fi

# create acptemplates.tar
if [ -d "acptemplates" ]; then
	cd acptemplates
	find * ! \( -name '.*' -prune \) | cpio -pdmu $2/acp/templates/
	cd ..
fi

# package requirements
if [ -d "requirements" ]; then
	cd requirements
	dirs=`find . -mindepth 1 -maxdepth 1 | grep -v .svn | grep -v tar.gz | sed "s/^\.\///"`
	cd ..

	for i in $dirs
	do
		sh ../update_files.sh requirements/$i $2
	done
fi

# package optionals
if [ -d "optionals" ]; then
	cd optionals
	dirs=`find . -mindepth 1 -maxdepth 1 | grep -v .svn | grep -v tar.gz | sed "s/^\.\///"`
	cd ..

	for i in $dirs
	do
		sh ../update_files.sh optionals/$i $2
	done
fi

echo ""
echo "<<<<<<<<<<<<<<<< ${1} wurde synchronisiert <<<"
echo "<<<<<<<<<<<<<<<< `date` <<<"
echo ""
