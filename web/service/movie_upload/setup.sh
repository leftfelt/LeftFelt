#!/bin/bash
#root権限でないと動作しない可能性あり
change_owner() {
	chown apache:apache uploads
	chown apache:apache public_html/Err
	chown apache:apache public_html/comments
	chown apache:apache public_html/debug
	chown apache:apache public_html/videos
	chown apache:apache public_html/thumbnail
}
change_mod(){
	chmod 777 templates_c
}
change_owner
change_mod
