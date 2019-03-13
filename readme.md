# Evaluation Project

This web app allows a user to upload an SQLite DB file with this file extension: `.db` or `.sqlite` and with [this SQLite schema](#SQLite-DB-schema) which will then be read by the web app and saved / imported onto a MySQL cloud database with [this MySQL schema](#MySQL-Cloud-DB-Schema).

After the user has imported at least one SQLite DB file successfully they can then submit SQL queries via a web form to see the data that was imported from the SQLite DB file as it is saved on the cloud database. 

Upon successfully querying the cloud the database the results are then returned to the user and shown on a table. 

#### SQLite DB schema

    CREATE TABLE `Tool` ( 
      `ToolId` INTEGER, 
      `Serial` TEXT, 
      `Model` TEXT, 
      `Manufacturer` TEXT, 
      PRIMARY KEY(`ToolId`) 
    )


    CREATE TABLE "CalibrationResult" 
    ( 
      `CalibrationResultId` INTEGER, 
      `RunTime` NUMERIC, 
      `OperatorId` TEXT, 
      `Voltmeter` INTEGER, 
      `Ammeter` INTEGER, 
      `Supply` INTEGER, 
      `Load` INTEGER, 
      `TickBox` INTEGER, 
      `Scanner` INTEGER, 
      `Jig` INTEGER, 
      `Tick_Firmware` TEXT, 
      `Tick_Guid` TEXT, 
      `Tick_HwId` TEXT, 
      `Tick_BatchId` INTEGER, 
      `Tick_Count` INTEGER, 
      `Tick_Serial` TEXT, 
      `Tick_AccelRes` INTEGER, 
      `Tick_VoltCalScale` REAL, 
      `Tick_VoltCalOffset` REAL, 
      `Tick_CurrCalScale` REAL, 
      `Tick_CurrCalOffset` REAL, 
      `Tick_EepromRes` INTEGER, 
      `Tick_FlashRes` INTEGER, 
      `CalStatus` INTEGER, 
      `Tick_ProductId` INTEGER, 
      `EndTime` NUMERIC, 
      `AAx` INTEGER, 
      `AAy` INTEGER, 
      `AAz` INTEGER, 
      `AOff` INTEGER, 
      `FlashId` TEXT, 
      `Prev_Tick_Firmware` TEXT, 
      FOREIGN KEY(`TickBox`) REFERENCES `Tool`(`ToolId`), 
      PRIMARY KEY(`CalibrationResultId`), 
      FOREIGN KEY(`Scanner`) REFERENCES `Tool`(`ToolId`), 
      FOREIGN KEY(`Jig`) REFERENCES `Tool`(`ToolId`), 
      FOREIGN KEY(`Supply`) REFERENCES `Tool`(`ToolId`), 
      FOREIGN KEY(`Load`) REFERENCES `Tool`(`ToolId`), 
      FOREIGN KEY(`Voltmeter`) REFERENCES `Tool`(`ToolId`), 
      FOREIGN KEY(`Ammeter`) REFERENCES `Tool`(`ToolId`) 
    )
    



#### (MySQL) Cloud DB Schema

    CREATE TABLE `CalibrationResult` (
      `CalibrationResultId` bigint(20) NOT NULL,
      `RunTime` datetime DEFAULT NULL,
      `OperatorId` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
      `Voltmeter` bigint(20) DEFAULT NULL,
      `Ammeter` bigint(20) DEFAULT NULL,
      `Supply` bigint(20) DEFAULT NULL,
      `Load` bigint(20) DEFAULT NULL,
      `TickBox` bigint(20) DEFAULT NULL,
      `Scanner` bigint(20) DEFAULT NULL,
      `Jig` bigint(20) DEFAULT NULL,
      `Tick_Firmware` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
      `Tick_Guid` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
      `Tick_HwId` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
      `Tick_BatchId` int(11) DEFAULT NULL,
      `Tick_Count` int(11) DEFAULT NULL,
      `Tick_Serial` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
      `Tick_AccelRes` int(11) DEFAULT NULL,
      `Tick_VoltCalScale` decimal(20,10) DEFAULT NULL,
      `Tick_VoltCalOffset` decimal(20,10) DEFAULT NULL,
      `Tick_CurrCalScale` decimal(20,10) DEFAULT NULL,
      `Tick_CurrCalOffset` decimal(20,10) DEFAULT NULL,
      `Tick_EepromRes` int(11) DEFAULT NULL,
      `Tick_FlashRes` int(11) DEFAULT NULL,
      `CalStatus` int(11) DEFAULT NULL,
      `Tick_ProductId` int(11) DEFAULT NULL,
      `EndTime` datetime DEFAULT NULL,
      `AAx` int(11) DEFAULT NULL,
      `AAy` int(11) DEFAULT NULL,
      `AAz` int(11) DEFAULT NULL,
      `AOff` int(11) DEFAULT NULL,
      `FlashId` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
      `Prev_Tick_Firmware` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
      KEY `calibrationresult_calibrationresultid_index` (`CalibrationResultId`),
      KEY `cr_tickbox_fk` (`TickBox`),
      KEY `cr_scanner_fk` (`Scanner`),
      KEY `cr_jig_fk` (`Jig`),
      KEY `cr_supply_fk` (`Supply`),
      KEY `cr_load_fk` (`Load`),
      KEY `cr_voltmeter_fk` (`Voltmeter`),
      KEY `cr_ammeter_fk` (`Ammeter`),
      CONSTRAINT `cr_ammeter_fk` FOREIGN KEY (`Ammeter`) REFERENCES `Tool` (`ToolId`),
      CONSTRAINT `cr_jig_fk` FOREIGN KEY (`Jig`) REFERENCES `Tool` (`ToolId`),
      CONSTRAINT `cr_load_fk` FOREIGN KEY (`Load`) REFERENCES `Tool` (`ToolId`),
      CONSTRAINT `cr_scanner_fk` FOREIGN KEY (`Scanner`) REFERENCES `Tool` (`ToolId`),
      CONSTRAINT `cr_supply_fk` FOREIGN KEY (`Supply`) REFERENCES `Tool` (`ToolId`),
      CONSTRAINT `cr_tickbox_fk` FOREIGN KEY (`TickBox`) REFERENCES `Tool` (`ToolId`),
      CONSTRAINT `cr_voltmeter_fk` FOREIGN KEY (`Voltmeter`) REFERENCES `Tool` (`ToolId`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

    CREATE TABLE `Tool` (
      `ToolId` bigint(20) NOT NULL,
      `Serial` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
      `Model` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
      `Manufacturer` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
      PRIMARY KEY (`ToolId`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
