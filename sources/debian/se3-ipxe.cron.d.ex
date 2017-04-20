#
# Regular cron jobs for the sambaedu-ipxe package
#
0 4	* * *	root	[ -x /usr/bin/sambaedu-ipxe_maintenance ] && /usr/bin/sambaedu-ipxe_maintenance
