#
# Regular cron jobs for the se3-ipxe package
#
0 4	* * *	root	[ -x /usr/bin/se3-ipxe_maintenance ] && /usr/bin/se3-ipxe_maintenance
