INSERT INTO `ACLPriviledges` (`ID`, `Name`, `Description`) VALUES
(1, 'view', 'view'),
(2, 'edit', 'edit'),
(3, 'add', 'add'),
(4, 'delete', 'delete');

INSERT INTO `ACLResources` (`ID`, `Name`, `Description`, `Category`, `ParentName`) VALUES
(1, 'public', 'Public Sections', 'Public', 'NULL');


INSERT INTO `ACLRole` (`ID`, `Name`, `Description`, `ParentName`) VALUES
(1, 'guest', 'Any public users', 'NULL');


INSERT INTO `ACLMap` (`ID`, `Role`, `Resources`, `Priviledges`, `Allow`) VALUES
(1, 'guest', 'public', 'view', 1);


INSERT INTO `ACLUsers` (`ID`, `ACLRole`, `Name`, `Username`, `Password`, `Email`, `Active`, `LastLogin`, `UserCreated`, `DateCreated`, `UserModified`, `DateModified`, `AfcasUserID`, `AfcasPassword`, `AfcasRole`, `MAID`, `ClubID`) VALUES
(1, 'system_admin', 'System Admin', 'system_admin', '67164a34f6a9ec4d82b87184d33e8ca6', 'guest@email.com', 1, '0000-00-00 00:00:00', '', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL);




