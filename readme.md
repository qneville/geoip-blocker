About This Thing
----------------

### *What does this do?*

The point of this is to generate shell scripts and be an effective resource for aggragating regional ip's for the sole purpose of blacklisting against attacks.

IPTables or Powershell Firewall rules are the intended targets for these scripts.

### *Why is the list so long?*

Good question. Those are all the regions supported by ipdeny.com.

### *Why doesn't anything work?*

Good question.

### *What's the deal with all the file read/writes?*

I threw all the important junk in the "data" folder. 

* ips.txt =
	This is where the aggragated IP list is stored

* banned.db.txt =
	This is where chosen regions are stored. It will always be shorter than regions.all.db.txt

* regions.all.db.txt =
	This is where all the regions are stored. Keep on the lookout for failures when generating lists. ipdeny.com may drop support for one or two of these in the future.

### *How do I use it?*

Install this on your own web server. You will want to remove public access to everything related to this, as security is next to nonexistent.

* /index.php =
	The GUI for managing the list of banned IPs

* /ips.php =
	Used by the iptables.sh.php template to download the aggragated list of IPs