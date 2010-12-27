#!/bin/bash
#
# packages filesystem as wcf package
# parameter 	(1) = directory (e.g. bbcode.google)
#		(2) = version output (optional)
#
# by Torben Brodt
if [ $1 ]; then
	PARAM=$1
else
	PARAM="de.easy-coding.wcf.contest"
fi

cd $PARAM

# fetch packagename and version
TITLE=`grep "<package name=" package.xml | cut -d '"' -f2`
VERSION=`grep "<version>" package.xml | cut -d ">" -f2 | cut -d "<" -f1`
FILENAME=`echo ${TITLE}.${VERSION}.tar.gz | sed "s/ //" | sed "s/ //"`

# and assign date
BUILDDATE=`date +"%Y-%m-%d"`
TAR_STRING=""
RECURSION=0

# version output
if [ $2 ]; then
	if [ "$2" = "-v" ]; then
		echo $FILENAME
		exit
	else
		RECURSION=`expr $2 + 1`
		if [ "$2" -lt "2" ]; then
			echo "RECURSION = $RECURSION"
		else
			# recursion counter
			echo "MAX_RECURSION = $RECURSION"
			exit
		fi
	fi
fi

# welcome output
echo ""
echo ">>> $TITLE wird erstellt >>>>>>>>>>>>>>>>>"
echo ""

# create files.tar
if [ -d "files" ]; then
	TAR_STRING="$TAR_STRING files.tar"
	cd files
	tar cvf ../files.tar * --exclude=*/.svn*
	cd ..
fi

# create pip.tar
if [ -d "pip" ]; then
	TAR_STRING="$TAR_STRING pip.tar"
	cd pip
	tar cvf ../pip.tar * --exclude=*/.svn*
	cd ..
fi

# create templates.tar
if [ -d "templates" ]; then
	TAR_STRING="$TAR_STRING templates.tar"
	cd templates
	tar cvf ../templates.tar * --exclude=*/.svn*
	cd ..
fi

# create acptemplates.tar
if [ -d "acptemplates" ]; then
	TAR_STRING="$TAR_STRING acptemplates.tar"
	cd acptemplates
	tar cvf ../acptemplates.tar * --exclude=*/.svn*
	cd ..
fi

# create styles.tar
if [ -d "styles" ]; then
	TAR_STRING="$TAR_STRING styles/*"
fi

# package requirements
if [ -d "requirements" ]; then
	cd requirements
	mkdir -p /tmp/${TITLE}/requirementsbackup
	cp *.tar.gz /tmp/${TITLE}/requirementsbackup
	dirs=`find . -mindepth 1 -maxdepth 1 -type d | grep -v .svn`
	cd ..

	for i in $dirs
	do
		PACKFILENAME=`sh ../make_package.sh requirements/$i -v`
		sh ../make_package.sh requirements/$i $RECURSION
		if [ -f "requirements/${PACKFILENAME}" ]; then
			mv requirements/${PACKFILENAME} requirements/$i.tar.gz
		else
			# remove version from filename and place in right directory
			mv ../${PACKFILENAME} requirements/$i.tar.gz
		fi
	done
	TAR_STRING="$TAR_STRING requirements/*.tar*"
fi

# package optionals
if [ -d "optionals" ]; then
	cd optionals
	mkdir -p /tmp/${TITLE}/optionalsbackup
	cp *.tar.gz /tmp/${TITLE}/optionalsbackup
	dirs=`find . -mindepth 1 -maxdepth 1 -type d | grep -v .svn`
	cd ..

	for i in $dirs
	do
		PACKFILENAME=`sh ../make_package.sh optionals/$i -v`
		sh ../make_package.sh optionals/$i $RECURSION
		if [ -f "optionals/${PACKFILENAME}" ]; then
			mv optionals/${PACKFILENAME} optionals/$i.tar.gz
		else
			# remove version from filename and place in right directory
			mv ../${PACKFILENAME} optionals/$i.tar.gz
		fi
	done
	TAR_STRING="$TAR_STRING optionals/*.tar*"
fi

# replacements in language files
#if [ -f "de.xml" ]; then
	#mv de.xml de.tmp
	#sed "s/VERSION/$VERSION/" de.tmp > de.xml
#fi
#if [ -f "en.xml" ]; then
	#mv en.xml en.tmp
	#sed "s/VERSION/$VERSION/" en.tmp > en.xml
#fi
#if [ -f "de-informal.xml" ]; then
	#mv de-informal.xml de-informal.tmp
	#sed "s/VERSION/$VERSION/" de-informal.tmp > de-informal.xml
#fi
#if [ -f "it.xml" ]; then
	#mv it.xml it.tmp
	#sed "s/VERSION/$VERSION/" it.tmp > it.xml
#fi
#if [ -f "hr.xml" ]; then
	#mv hr.xml hr.tmp
	#sed "s/VERSION/$VERSION/" hr.tmp > hr.xml
#fi
#if [ -f "fr.xml" ]; then
	#mv fr.xml fr.tmp
	#sed "s/VERSION/$VERSION/" fr.tmp > fr.xml
#fi

# replacements in package.xml
mv package.xml package.tmp
sed "s/DATE/$BUILDDATE/" package.tmp > package.xml

# remove old package
if [ -f "../${FILENAME}" ] ; then
	rm ../${FILENAME}
fi

# append sql and diff files to package
VARX=`find *.diff 2>/dev/null`
if [ "$VARX" ]; then
	TAR_STRING="$TAR_STRING *.diff"
fi
VARX=`find *.sql 2>/dev/null`
if [ "$VARX" ]; then
	TAR_STRING="$TAR_STRING *.sql"
fi

VARX=`find *_update.tar 2>/dev/null`
if [ "$VARX" ]; then
	TAR_STRING="$TAR_STRING *_update.tar"
fi

if [ -f "LICENSE" ]; then
	TAR_STRING="$TAR_STRING LICENSE"
fi

# create new package
tar cfz ${FILENAME} *.xml $TAR_STRING
mv ${FILENAME} ..

# rename back
mv package.tmp package.xml
#if [ -f "de.xml" ]; then
#	mv de.tmp de.xml
#fi
#if [ -f "en.xml" ]; then
#	mv en.tmp en.xml
#fi
#if [ -f "de-informal.xml" ]; then
#	mv de-informal.tmp de-informal.xml
#fi
#if [ -f "it.xml" ]; then
#	mv it.tmp it.xml
#fi
#if [ -f "fr.xml" ]; then
#	mv fr.tmp fr.xml
#fi
#if [ -f "hr.xml" ]; then
#	mv hr.tmp hr.xml
#fi

# remove tmp files
if [ -f "files.tar" ]; then
	rm files.tar
fi
if [ -f "acptemplates.tar" ]; then
	rm acptemplates.tar
fi
if [ -f "templates.tar" ]; then
	rm templates.tar
fi
if [ -f "pip.tar" ]; then
	rm pip.tar
fi
if [ -d "optionals" ]; then
	rm -f optionals/*.tar.gz
	mv /tmp/${TITLE}/optionalsbackup/*.tar.gz optionals
fi
if [ -d "requirements" ]; then
	rm -f requirements/*.tar.gz
	mv /tmp/${TITLE}/requirementsbackup/*.tar.gz requirements
fi

echo ""
echo "<<<<<<<<<<<<<<<< ${FILENAME} wurde erstellt <<<"
echo "<<<<<<<<<<<<<<<< `date` <<<"
echo ""
