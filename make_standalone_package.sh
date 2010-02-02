#!/bin/bash
#
# builds a standalone package
# by Torben Brodt

original=`sh make_package.sh standalone/WCFSetup/install/packages/com.woltlab.wcf/ -v`
sh make_package.sh standalone/WCFSetup/install/packages/com.woltlab.wcf/
mv standalone/WCFSetup/install/packages/$original standalone/WCFSetup/install/packages/com.woltlab.wcf.tar.gz
gunzip -f standalone/WCFSetup/install/packages/com.woltlab.wcf.tar.gz

original=`sh make_package.sh standalone/WCFSetup/install/packages/de.easy-coding.wcf.contest.standalone/ -v`
sh make_package.sh standalone/WCFSetup/install/packages/de.easy-coding.wcf.contest.standalone/
mv standalone/WCFSetup/install/packages/$original standalone/WCFSetup/install/packages/de.easy-coding.wcf.contest.standalone.tar.gz
gunzip -f standalone/WCFSetup/install/packages/de.easy-coding.wcf.contest.standalone.tar.gz

original=`sh make_package.sh de.easy-coding.wcf.contest/ -v`
sh make_package.sh de.easy-coding.wcf.contest/
mv $original standalone/WCFSetup/install/packages/de.easy-coding.wcf.contest.tar.gz
gunzip -f standalone/WCFSetup/install/packages/de.easy-coding.wcf.contest.tar.gz

cd standalone/WCFSetup
tar cvfz ../WCFSetup.tar.gz * --exclude=*/.svn* --exclude=install/packages/com.woltlab.wcf --exclude=install/packages/de.easy-coding.wcf.contest.standalone
rm -f install/packages/com.woltlab.wcf.tar
rm -f install/packages/de.easy-coding.wcf.contest.tar
rm -f install/packages/de.easy-coding.wcf.contest.standalone.tar
cd ..

rm -f easycodingcontest.zip
zip -r easycodingcontest.zip * --exclude=WCFSetup/*
mv easycodingcontest.zip ..
rm WCFSetup.tar.gz
cd ..



# the following wcf packages are currently under wbb license
# - com.woltlab.wcf.acp.content.attachment.tar
# - com.woltlab.wcf.acp.content.bbcode.tar
# - com.woltlab.wcf.acp.content.help.tar
# - com.woltlab.wcf.acp.content.smiley.tar
# - com.woltlab.wcf.acp.display.language.tar
# - com.woltlab.wcf.acp.display.pageMenu.tar
# - com.woltlab.wcf.acp.display.style.tar
# - com.woltlab.wcf.acp.display.template.tar
# - com.woltlab.wcf.acp.system.db.tar
# - com.woltlab.wcf.acp.system.stats.tar
# - com.woltlab.wcf.acp.user.avatar.tar
# - com.woltlab.wcf.acp.user.option.tar
# - com.woltlab.wcf.acp.user.rank.tar
# - com.woltlab.wcf.data.message.pm.tar
# - com.woltlab.wcf.data.user.messenger.tar
# - com.woltlab.wcf.form.user.group.tar
# - com.woltlab.wcf.form.user.signature.tar
# - com.woltlab.wcf.message.multiQuote.tar
# - com.woltlab.wcf.page.user.membersList.team.tar
# - com.woltlab.wcf.page.user.usersOnline.tar
# - com.woltlab.wcf.user.attachment.tar
# - com.woltlab.wcf.user.infraction.tar
# - com.woltlab.wcf.user.security.login.ta
