--procedure for adding an asset to the database
DELIMITER //
CREATE PROCEDURE addAsset
(IN iname VARCHAR(255), iowner VARCHAR(10), icurr VARCHAR(10), iassetCondition VARCHAR(6), inotes TEXT)
BEGIN
	INSERT INTO assets (name, owner, curr, assetCondition, notes) VALUES (iname, iowner, icurr, iassetCondition, inotes);
END //
DELIMITER ;
