## Table of contents
* [General info](#general-info)
* [Workspace](#workspace-page)
* [Technologies](#technologies)
* [Database design](#database-design)


## General info
The goal is to build a web-based collaboration system similar to Slack that allows members of a team to build workspaces, create channels within these workspaces, and then communicate within these channels.

## Workspace page
![alt text](https://raw.githubusercontent.com/VibhaChoudhary/Snickr/master/workspace_page.png)
	
## Technologies
### Web server
* Apache 2.4.38
* PHP 7.3.3
### Database server
* 10.1.38 MariaDB
### Front-End
* HTML5 
* CSS 
* Jquery 
* Bootsrap

## Database design
* User (uid, upassword, uemail, unickname, uphone, ujob, udp, utoken, uverified, join_ts)
* Workspace (wid, wname, wpurpose, wcreator, wurl, create_ts)
	FOREIGN KEY (wcreator) REFERENCES User (uid) 
* Workspace_Member (wid, uid, join_ts)
	FOREIGN KEY (wid) REFERENCES Workspace (wid)
	FOREIGN KEY (uid) REFERENCES User (uid)

* Channel (cid, wid, ccreator, cname, cpurpose, cprivate, cdefault, create_ts)
	FOREIGN KEY (wid) REFERENCES Workspace (wid) 
	FOREIGN KEY (wid,ccreator) REFERENCES Workspace_Member (wid,uid)

* Channel_Member (cid, uid, cstarred, join_ts)
	FOREIGN KEY (cid) REFERENCES Channel (cid)
	FOREIGN KEY (uid) REFERENCES User (uid)

* Workspace_Admin (wid, uid, add_ts)
	FOREIGN KEY (wid,uid) REFERENCES Workspace_Member (wid,uid)
	
* Channel_Message (mid, cid, fromuid, mcontent, message_ts)
	FOREIGN KEY (cid) REFERENCES Channel (cid)
	FOREIGN KEY (fromuid) REFERENCES User (uid)
	
* Direct_Message (dmid, wid, fromuid, touid, dmcontent, message_ts)
	FOREIGN KEY (wid) REFERENCES Workspace (wid)
	FOREIGN KEY (fromuid) REFERENCES User (uid)
	FOREIGN KEY (touid) REFERENCES User (uid)

* Workspace_Invite (wsid, wid, toemail, fromuid, invite_ts)
	FOREIGN KEY (wid) REFERENCES Workspace (wid)
	FOREIGN KEY (fromuid) REFERENCES User (uid)

* Channel_Invite (chid, cid, touid, fromuid, invite_ts)
	FOREIGN KEY (cid) REFERENCES Channel (cid)
	FOREIGN KEY (fromuid) REFERENCES User (uid)
	FOREIGN KEY (touid) REFERENCES User (uid)

* Permission (pid, pname, pallowed)
* Workspace_Permission (wid, pid, pallowed)
	FOREIGN KEY (wid) REFERENCES Workspace (wid)
	FOREIGN KEY (pid) REFERENCES Permission (pid)

* Preferences (prid, prname, default_value)
* User_Preferences (uid, prid, wid, prvalue)
	FOREIGN KEY (uid) REFERENCES User (uid)
	FOREIGN KEY (wid) REFERENCES Workspace (wid) FOREIGN KEY (prid) REFERENCES Preferences (prid)

