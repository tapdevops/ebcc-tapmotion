<?php
	header("Refresh:3600");
	
	
	include("config/db_connect.php");
	$con = connect();
	
	$sql  = "SELECT     'TEXT||| ||| ||| |||' as STYLE3,
        BUSSINES_AREA as \"Business Area\", 
        'TEXT||| ||| ||| |||' as STYLE4,
        AFDELING as \"Afdeling\", 
        CASE
            WHEN NVL(TAKSASI_PANEN,0) = 0
            THEN 'DECIMAL|||#cccccc|||#ffffff||| |||'
            ELSE 'DECIMAL||| ||| ||| |||'
        END as STYLE5,
        TAKSASI_PANEN as \"Panen (ton)\", 
        'DECIMAL||| ||| ||| |||' as STYLE6,
        PERSEN_BRONDOLAN as \"Brondolan (%)\",
        'DECIMAL||| ||| ||| |||' as STYLE7,
        TAKSASI_KIRIM as \"Kirim (ton)\", 
        'DECIMAL||| ||| ||| |||' as STYLE8,
        BERAT_TERIMA_MILL as \"Terima (ton)\", 
        CASE
            WHEN NVL(TO_CHAR(RESTAN1 + RESTAN2 + RESTAN3),'N/A') = 'N/A'
            THEN 'DECIMAL|||#cccccc|||#ffffff||| |||'
            WHEN TO_CHAR(RESTAN1 + RESTAN2 + RESTAN3) = 0
            THEN 'DECIMAL|||#00b034|||#ffffff||| |||'
            ELSE 'DECIMAL|||#ff0000|||#ffffff||| |||'
        END as STYLE9,
        CASE
            WHEN NVL(TO_CHAR(RESTAN1 + RESTAN2 + RESTAN3),'N/A') = 'N/A'
            THEN 'N/A'
            ELSE TO_CHAR(RESTAN1 + RESTAN2 + RESTAN3)
        END as \"Saldo Taksasi Restan (ton)\",
        'INTEGER||| ||| ||| |||' as STYLE13,
        JML_JJG_RESTAN_SALDO as \"Saldo Taksasi Restan (jjg)\", 
        CASE
            WHEN NVL(TO_CHAR(RESTAN1),'N/A') = 'N/A'
            THEN 'DECIMAL|||#cccccc|||#ffffff||| |||'
            WHEN RESTAN1 = 0
            THEN 'DECIMAL|||#00b034|||#ffffff||| |||'
            ELSE 'DECIMAL|||#ff0000|||#ffffff||| |||'
        END as STYLE10,
        CASE
            WHEN NVL(TO_CHAR(RESTAN1),'N/A') = 'N/A'
            THEN 'N/A'
            ELSE TO_CHAR(RESTAN1)
        END as \"Restan Hari Ini (ton)\",
        'INTEGER||| ||| ||| |||' as STYLE14,
        JML_JJG_RESTAN_1 as \"Restan Hari Ini (jjg)\", 
        CASE
            WHEN NVL(TO_CHAR(RESTAN2),'N/A') = 'N/A'
            THEN 'DECIMAL|||#cccccc|||#ffffff||| |||'
            WHEN RESTAN2 = 0
            THEN 'DECIMAL|||#00b034|||#ffffff||| |||'
            ELSE 'DECIMAL|||#ff0000|||#ffffff||| |||'
        END as STYLE11,
        CASE
            WHEN NVL(TO_CHAR(RESTAN2),'N/A') = 'N/A'
            THEN 'N/A'
            ELSE TO_CHAR(RESTAN2)
        END as \"Restan 2 Hari (ton)\",
        CASE
            WHEN NVL(TO_CHAR(RESTAN3),'N/A') = 'N/A'
            THEN 'DECIMAL|||#cccccc|||#ffffff||| |||'
            WHEN RESTAN3 = 0
            THEN 'DECIMAL|||#00b034|||#ffffff||| |||'
            ELSE 'DECIMAL|||#ff0000|||#ffffff||| |||'
        END as STYLE12,
        CASE
            WHEN NVL(TO_CHAR(RESTAN3),'N/A') = 'N/A'
            THEN 'N/A'
            ELSE TO_CHAR(RESTAN3)
        END as \"Restan 3 - 15 Hari (ton)\"
FROM (
    SELECT    BCC.TGL_REPORT, 
            BCC.KODE_BA, 
            BCC.BUSSINES_AREA, 
            BCC.AFDELING, 
            BCC.TAKSASI_PANEN, 
            BCC.PERSEN_BRONDOLAN,
            BCC.TAKSASI_KIRIM,
            NVL(MILL.BERAT_TERIMA_MILL,0) as BERAT_TERIMA_MILL,
            BCC.RESTAN1,
            BCC.RESTAN2,
            BCC.RESTAN3,
            BCC.JML_JJG_RESTAN_SALDO,
            BCC.JML_JJG_RESTAN_1
    FROM (    
        SELECT     TGL_REPORT, 
                KODE_BA, 
                BUSSINES_AREA, 
                AFDELING, 
                (NVL(SUM(TAKSASI_PANEN),0) / 1000) as TAKSASI_PANEN, 
                (NVL((SUM(BRONDOLAN) / NULLIF(SUM(TAKSASI_PANEN),0) * 100),0)) as PERSEN_BRONDOLAN,
                (NVL(SUM(TAKSASI_KIRIM),0) / 1000) as TAKSASI_KIRIM,
                (NVL(SUM(RESTAN1),0) /1000) as RESTAN1,
                (NVL(SUM(RESTAN2),0) /1000) as RESTAN2,
                (NVL(SUM(RESTAN3),0) /1000) as RESTAN3,
                (NVL(SUM(JML_JJG_RESTAN_SALDO),0)) as JML_JJG_RESTAN_SALDO,
                (NVL(SUM(JML_JJG_RESTAN_1),0)) as JML_JJG_RESTAN_1
        FROM (
            SELECT     TRUNC(SYSDATE-1) as TGL_REPORT, 
                    SUBSTR(MS_BLOCK.ID_BA_AFD,1,4) KODE_BA, 
                    T3.EST_NAME BUSSINES_AREA, 
                    SUBSTR(MS_BLOCK.ID_BA_AFD,5,1) AFDELING, 
                    MS_BLOCK.ID_BLOK BLOCK_CODE,
                    NVL(T1.JML_BRD_PANEN,0) BRONDOLAN,
                    NVL(T4.BJR_PREV,0) BJR_PREV,
                    ( NVL(T1.JML_JJG_PANEN,0) * NVL(T4.BJR_PREV,0) ) TAKSASI_PANEN,
                    ( NVL(T1.JML_JJG_ANGKUT,0) * NVL(T4.BJR_PREV,0) ) TAKSASI_KIRIM, 
            (
                SELECT NVL(SUM(T2A.KG_TAKSASI),0)
                FROM TAP_DW.TR_HV_RESTANT_DETAIL@DWH_LINK T2A
                WHERE T2A.NATIONAL=T1.NATIONAL
                AND T2A.REGION_CODE=T1.REGION_CODE
                AND T2A.COMP_CODE=T1.COMP_CODE
                AND T2A.EST_CODE=T1.EST_CODE
                AND T2A.WERKS=T1.WERKS
                AND T2A.AFD_CODE=T1.AFD_CODE
                AND T2A.BLOCK_CODE=T1.BLOCK_CODE
                AND T2A.BLOCK_CODE_GIS=T1.BLOCK_CODE_GIS
                AND T2A.TGL_REPORT=T1.TGL_REPORT
                AND T2A.SUB_BLOCK_CODE=T1.SUB_BLOCK_CODE
                AND T2A.TPH_RESTANT_DAY = 1
                --AND T2A.FLAG_NAB = 'NAB ADA'
            ) RESTAN1,
            (
                SELECT NVL(SUM(T2B.KG_TAKSASI),0)
                FROM TAP_DW.TR_HV_RESTANT_DETAIL@DWH_LINK T2B
                WHERE T2B.NATIONAL=T1.NATIONAL
                AND T2B.REGION_CODE=T1.REGION_CODE
                AND T2B.COMP_CODE=T1.COMP_CODE
                AND T2B.EST_CODE=T1.EST_CODE
                AND T2B.WERKS=T1.WERKS
                AND T2B.AFD_CODE=T1.AFD_CODE
                AND T2B.BLOCK_CODE=T1.BLOCK_CODE
                AND T2B.BLOCK_CODE_GIS=T1.BLOCK_CODE_GIS
                AND T2B.TGL_REPORT=T1.TGL_REPORT
                AND T2B.SUB_BLOCK_CODE=T1.SUB_BLOCK_CODE
                AND T2B.TPH_RESTANT_DAY = 2
                --AND T2B.FLAG_NAB = 'NAB ADA'
            ) RESTAN2,
            (
                SELECT NVL(SUM(T2C.KG_TAKSASI),0)
                FROM TAP_DW.TR_HV_RESTANT_DETAIL@DWH_LINK T2C
                WHERE T2C.NATIONAL=T1.NATIONAL
                AND T2C.REGION_CODE=T1.REGION_CODE
                AND T2C.COMP_CODE=T1.COMP_CODE
                AND T2C.EST_CODE=T1.EST_CODE
                AND T2C.WERKS=T1.WERKS
                AND T2C.AFD_CODE=T1.AFD_CODE
                AND T2C.BLOCK_CODE=T1.BLOCK_CODE
                AND T2C.BLOCK_CODE_GIS=T1.BLOCK_CODE_GIS
                AND T2C.TGL_REPORT=T1.TGL_REPORT
                AND T2C.SUB_BLOCK_CODE=T1.SUB_BLOCK_CODE
                AND T2C.TPH_RESTANT_DAY BETWEEN 3 AND 15
                --AND T2C.FLAG_NAB = 'NAB ADA'
            ) RESTAN3,
            (
                SELECT NVL(SUM(T2D.JML_JJG),0)
                FROM TAP_DW.TR_HV_RESTANT_DETAIL@DWH_LINK T2D
                WHERE T2D.NATIONAL=T1.NATIONAL
                AND T2D.REGION_CODE=T1.REGION_CODE
                AND T2D.COMP_CODE=T1.COMP_CODE
                AND T2D.EST_CODE=T1.EST_CODE
                AND T2D.WERKS=T1.WERKS
                AND T2D.AFD_CODE=T1.AFD_CODE
                AND T2D.BLOCK_CODE=T1.BLOCK_CODE
                AND T2D.BLOCK_CODE_GIS=T1.BLOCK_CODE_GIS
                AND T2D.TGL_REPORT=T1.TGL_REPORT
                AND T2D.SUB_BLOCK_CODE=T1.SUB_BLOCK_CODE
                AND T2D.TPH_RESTANT_DAY BETWEEN 1 AND 15
                --AND T2D.FLAG_NAB = 'NAB ADA'
            ) JML_JJG_RESTAN_SALDO,
            (
                SELECT NVL(SUM(T2E.JML_JJG),0)
                FROM TAP_DW.TR_HV_RESTANT_DETAIL@DWH_LINK T2E
                WHERE T2E.NATIONAL=T1.NATIONAL
                AND T2E.REGION_CODE=T1.REGION_CODE
                AND T2E.COMP_CODE=T1.COMP_CODE
                AND T2E.EST_CODE=T1.EST_CODE
                AND T2E.WERKS=T1.WERKS
                AND T2E.AFD_CODE=T1.AFD_CODE
                AND T2E.BLOCK_CODE=T1.BLOCK_CODE
                AND T2E.BLOCK_CODE_GIS=T1.BLOCK_CODE_GIS
                AND T2E.TGL_REPORT=T1.TGL_REPORT
                AND T2E.SUB_BLOCK_CODE=T1.SUB_BLOCK_CODE
                AND T2E.TPH_RESTANT_DAY = '1'
                --AND T2E.FLAG_NAB = 'NAB ADA'
            ) JML_JJG_RESTAN_1
            FROM EBCC.T_BLOK MS_BLOCK
            /* JOIN MS_BLOCK - T1 */
            LEFT JOIN TAP_DW.TR_HV_RESTANT_HEADER@DWH_LINK T1
            ON MS_BLOCK.ID_BA_AFD = T1.WERKS || T1.AFD_CODE
            AND MS_BLOCK.ID_BLOK = T1.BLOCK_CODE
            AND TO_CHAR(T1.TGL_REPORT,'YYYYMMDD')=TO_CHAR(SYSDATE-1,'YYYYMMDD')
            /* JOIN T1 - T3 */
            LEFT JOIN TAP_DW.TM_EST@DWH_LINK T3
            ON T3.WERKS = SUBSTR(MS_BLOCK.ID_BA_AFD,1,4)
            /* JOIN T1 - T4 */
            LEFT JOIN TAP_DW.TR_HV_BJR@DWH_LINK T4 
            ON T1.COMP_CODE=T4.COMP_CODE
            AND T1.EST_CODE=T4.EST_CODE
            AND T1.AFD_CODE=T4.AFD_CODE
            AND T1.SUB_BLOCK_CODE=T4.SUB_BLOCK_CODE
            AND TO_CHAR(T4.SPMON,'YYYYMM')=TO_CHAR(SYSDATE-1,'YYYYMM')
            /* PARAMETER */
            WHERE ( MS_BLOCK.INACTIVE_DATE IS NULL OR TRUNC(MS_BLOCK.INACTIVE_DATE) > TRUNC(SYSDATE-1) )
                AND SUBSTR(MS_BLOCK.ID_BA_AFD,1,2) = '21' -- FILTER PT
        )
        GROUP BY TGL_REPORT, KODE_BA, BUSSINES_AREA, AFDELING
    ) BCC
    LEFT JOIN (
        SELECT    COMP_CODE,
                WERKS,
                AFD_CODE,
                DATEOUT,
                SUM(BERAT_TERIMA_BLOCK) / 1000 as BERAT_TERIMA_MILL
        FROM (
            SELECT     BCC.*,
                    SWS.DATEOUT,
                    SWS.NETTOBEFOREGRADING,
                    SWS.FFB_UNITS,
                    ( BCC.ESTIMASI_BERAT / NULLIF(BCC.ESTIMASI_BERAT_PER_NAB,0) * SWS.NETTOBEFOREGRADING ) as BERAT_TERIMA_BLOCK
            FROM (
                SELECT     WBTICKET.*,
                        VENDOR.COMPANYCODE
                FROM (
                    SELECT COMPANYCODE as COMP_MILL, MILLPLANT, TICKETNO, DATEOUT, VENDORID, NETTOBEFOREGRADING, FFB_UNITS, SPBNO as SPB
                    FROM WB_STAGING.WB_PURCHASE
                    WHERE MATERIALID = '501010001'
                    AND DATEOUT = TO_CHAR(SYSDATE-1, 'RRRRMMDD')
                ) WBTICKET
                LEFT JOIN WB_STAGING.WB_VENDOR_PARAMETER VENDOR
                    ON TRIM(UPPER(WBTICKET.VENDORID)) = TRIM(UPPER(VENDOR.VENDORID))
                WHERE VENDOR.COMPANYCODE IS NOT NULL
            ) SWS
            LEFT JOIN (
                SELECT     BCC1.COMP_CODE,
                        BCC1.NO_NAB,
                        BCC1.WERKS,
                        BCC1.AFD_CODE,
                        BCC1.BLOCK_CODE,
                        BCC1.JJG_KIRIM,
                        BCC1.BJR,
                        BCC1.ESTIMASI_BERAT,
                        DATA_BCC_PER_NAB.ESTIMASI_BERAT_PER_NAB,
                        DATA_BCC_PER_NAB.JJG_KIRIM_PER_NAB
                FROM (        
                    SELECT    BCC.COMP_CODE,
                            BCC.NO_NAB,
                            BCC.WERKS,
                            BCC.AFD_CODE,
                            BCC.BLOCK_CODE,
                            SUM(BCC.JJG_KIRIM) as JJG_KIRIM,
                            ( MAX(BJR.BJR_PREV) ) as BJR,
                            ( SUM(BCC.JJG_KIRIM) * (MAX(BJR.BJR_PREV)) ) as ESTIMASI_BERAT
                    FROM (
                        SELECT    COMP_CODE,
                                NO_NAB,
                                WERKS,
                                AFD_CODE,
                                BLOCK_CODE,
                                TANGGAL_RENCANA,
                                SUM (DECODE (KETERANGAN, 'BUNCH_SEND', QTY, 0)) AS JJG_KIRIM
                        FROM (
                            SELECT     TBA.ID_CC as COMP_CODE,
                                    NAB.NO_NAB,
                                    TBA.ID_BA as WERKS,
                                    TA.ID_AFD as AFD_CODE,
                                    TB.ID_BLOK as BLOCK_CODE,
                                    THRP.TANGGAL_RENCANA,
                                    THK.ID_KUALITAS,
                                    THK.QTY,
                                    PARAM.KETERANGAN
                            FROM (
                                SELECT     ID_RENCANA,
                                        TANGGAL_RENCANA, 
                                        NIK_PEMANEN
                                FROM EBCC.T_HEADER_RENCANA_PANEN
                            )THRP
                            LEFT JOIN EBCC.T_DETAIL_RENCANA_PANEN TDRP
                                ON THRP.ID_RENCANA = TDRP.ID_RENCANA
                            LEFT JOIN EBCC.T_HASIL_PANEN THP
                                ON THP.ID_RENCANA = TDRP.ID_RENCANA
                                AND THP.NO_REKAP_BCC = TDRP.NO_REKAP_BCC
                            LEFT JOIN EBCC.T_HASILPANEN_KUALTAS THK    
                                ON THP.NO_BCC = THK.ID_BCC
                                AND THP.ID_RENCANA = THK.ID_RENCANA
                            LEFT JOIN EBCC.T_NAB NAB
                                ON NAB.ID_NAB_TGL = THP.ID_NAB_TGL
                            LEFT JOIN EBCC.T_BLOK TB
                                ON TDRP.ID_BA_AFD_BLOK = TB.ID_BA_AFD_BLOK
                            LEFT JOIN EBCC.T_AFDELING TA
                                ON TB.ID_BA_AFD = TA.ID_BA_AFD
                            LEFT JOIN EBCC.T_BUSSINESSAREA TBA
                                ON TBA.ID_BA = TA.ID_BA
                            LEFT JOIN (
                                SELECT BA_CODE, ID_KUALITAS, KETERANGAN
                                FROM EBCC.T_PARAMETER_BUNCH
                                WHERE KETERANGAN = 'BUNCH_SEND'
                            ) PARAM
                                ON THK.ID_KUALITAS = PARAM.ID_KUALITAS
                                AND TA.ID_BA = PARAM.BA_CODE
                            WHERE NAB.TGL_NAB BETWEEN TRUNC(SYSDATE-1-31) AND TRUNC(SYSDATE-1)
                        )
                        GROUP BY COMP_CODE,
                                NO_NAB,
                                WERKS,
                                AFD_CODE,
                                BLOCK_CODE,
                                TANGGAL_RENCANA
                    ) BCC
                    LEFT JOIN TAP_DW.TR_HV_BJR@DWH_LINK BJR 
                        ON BJR.COMP_CODE || BJR.EST_CODE = BCC.WERKS
                        AND BJR.AFD_CODE = BCC.AFD_CODE
                        AND BJR.SUB_BLOCK_CODE = BCC.BLOCK_CODE
                        AND TO_CHAR(BJR.SPMON,'RRRRMM') = TO_CHAR(BCC.TANGGAL_RENCANA, 'RRRRMM')
                    GROUP BY BCC.COMP_CODE,
                            BCC.NO_NAB,
                            BCC.WERKS,
                            BCC.AFD_CODE,
                            BCC.BLOCK_CODE    
                ) BCC1
                LEFT JOIN (
                    SELECT     COMP_CODE,
                            NO_NAB,
                            SUM(JJG_KIRIM) as JJG_KIRIM_PER_NAB,
                            SUM(ESTIMASI_BERAT) as ESTIMASI_BERAT_PER_NAB
                    FROM (
                        SELECT    BCC.COMP_CODE,
                                BCC.NO_NAB,
                                BCC.WERKS,
                                BCC.AFD_CODE,
                                BCC.BLOCK_CODE,
                                SUM(BCC.JJG_KIRIM) as JJG_KIRIM,
                                ( MAX(BJR.BJR_PREV) ) as BJR,
                                ( SUM(BCC.JJG_KIRIM) * (MAX(BJR.BJR_PREV)) ) as ESTIMASI_BERAT
                        FROM (
                            SELECT    COMP_CODE,
                                    NO_NAB,
                                    WERKS,
                                    AFD_CODE,
                                    BLOCK_CODE,
                                    TANGGAL_RENCANA,
                                    SUM (DECODE (KETERANGAN, 'BUNCH_SEND', QTY, 0)) AS JJG_KIRIM
                            FROM (
                                SELECT     TBA.ID_CC as COMP_CODE,
                                        NAB.NO_NAB,
                                        TBA.ID_BA as WERKS,
                                        TA.ID_AFD as AFD_CODE,
                                        TB.ID_BLOK as BLOCK_CODE,
                                        THRP.TANGGAL_RENCANA,
                                        THK.ID_KUALITAS,
                                        THK.QTY,
                                        PARAM.KETERANGAN
                                FROM (
                                    SELECT     ID_RENCANA,
                                            TANGGAL_RENCANA, 
                                            NIK_PEMANEN
                                    FROM EBCC.T_HEADER_RENCANA_PANEN
                                )THRP
                                LEFT JOIN EBCC.T_DETAIL_RENCANA_PANEN TDRP
                                    ON THRP.ID_RENCANA = TDRP.ID_RENCANA
                                LEFT JOIN EBCC.T_HASIL_PANEN THP
                                    ON THP.ID_RENCANA = TDRP.ID_RENCANA
                                    AND THP.NO_REKAP_BCC = TDRP.NO_REKAP_BCC
                                LEFT JOIN EBCC.T_HASILPANEN_KUALTAS THK    
                                    ON THP.NO_BCC = THK.ID_BCC
                                    AND THP.ID_RENCANA = THK.ID_RENCANA
                                LEFT JOIN EBCC.T_NAB NAB
                                    ON NAB.ID_NAB_TGL = THP.ID_NAB_TGL
                                LEFT JOIN EBCC.T_BLOK TB
                                    ON TDRP.ID_BA_AFD_BLOK = TB.ID_BA_AFD_BLOK
                                LEFT JOIN EBCC.T_AFDELING TA
                                    ON TB.ID_BA_AFD = TA.ID_BA_AFD
                                LEFT JOIN EBCC.T_BUSSINESSAREA TBA
                                    ON TBA.ID_BA = TA.ID_BA
                               LEFT JOIN (
                                    SELECT BA_CODE, ID_KUALITAS, KETERANGAN
                                    FROM EBCC.T_PARAMETER_BUNCH
                                    WHERE KETERANGAN = 'BUNCH_SEND'
                                ) PARAM
                                    ON THK.ID_KUALITAS = PARAM.ID_KUALITAS
                                    AND TA.ID_BA = PARAM.BA_CODE
                                WHERE NAB.TGL_NAB BETWEEN TRUNC(SYSDATE-1-31) AND TRUNC(SYSDATE-1)
                            )
                            GROUP BY COMP_CODE,
                                    NO_NAB,
                                    WERKS,
                                    AFD_CODE,
                                    BLOCK_CODE,
                                    TANGGAL_RENCANA
                        ) BCC
                        LEFT JOIN TAP_DW.TR_HV_BJR@DWH_LINK BJR 
                            ON BJR.COMP_CODE || BJR.EST_CODE = BCC.WERKS
                            AND BJR.AFD_CODE = BCC.AFD_CODE
                            AND BJR.SUB_BLOCK_CODE = BCC.BLOCK_CODE
                            AND TO_CHAR(BJR.SPMON,'RRRRMM') = TO_CHAR(BCC.TANGGAL_RENCANA, 'RRRRMM')
                        GROUP BY BCC.COMP_CODE,
                                BCC.NO_NAB,
                                BCC.WERKS,
                                BCC.AFD_CODE,
                                BCC.BLOCK_CODE
                    )
                    GROUP BY COMP_CODE,
                            NO_NAB
                ) DATA_BCC_PER_NAB
                    ON DATA_BCC_PER_NAB.COMP_CODE = BCC1.COMP_CODE
                    AND DATA_BCC_PER_NAB.NO_NAB = BCC1.NO_NAB
            ) BCC
                ON SWS.COMPANYCODE = BCC.COMP_CODE
                AND SWS.SPB = BCC.NO_NAB
        )
        GROUP BY COMP_CODE,
                WERKS,
                AFD_CODE,
                DATEOUT
    ) MILL
        ON MILL.WERKS = BCC.KODE_BA
        AND MILL.AFD_CODE = BCC.AFDELING
    ORDER BY BCC.KODE_BA, BCC.AFDELING
)
UNION ALL
SELECT     'TEXT|||#000000|||#ffffff||| |||' as STYLE3,
        'TOTAL PT' as \"Business Area\", 
        'TEXT|||#000000|||#ffffff||| |||' as STYLE4,
        NULL as \"Afdeling\", 
        CASE
            WHEN NVL(TAKSASI_PANEN,0) = 0
            THEN 'DECIMAL|||#cccccc|||#ffffff||| |||'
            ELSE 'DECIMAL||| ||| ||| |||'
        END as STYLE5,
        TAKSASI_PANEN as \"Panen (ton)\", 
        'DECIMAL||| ||| ||| |||' as STYLE6,
        PERSEN_BRONDOLAN as \"Brondolan (%)\",
        'DECIMAL||| ||| ||| |||' as STYLE7,
        TAKSASI_KIRIM as \"Kirim (ton)\", 
        'DECIMAL||| ||| ||| |||' as STYLE8,
        BERAT_TERIMA_MILL as \"Terima (ton)\", 
        CASE
            WHEN NVL(TO_CHAR(RESTAN1 + RESTAN2 + RESTAN3),'N/A') = 'N/A'
            THEN 'DECIMAL|||#cccccc|||#ffffff||| |||'
            WHEN TO_CHAR(RESTAN1 + RESTAN2 + RESTAN3) = 0
            THEN 'DECIMAL|||#00b034|||#ffffff||| |||'
            ELSE 'DECIMAL|||#ff0000|||#ffffff||| |||'
        END as STYLE9,
        CASE
            WHEN NVL(TO_CHAR(RESTAN1 + RESTAN2 + RESTAN3),'N/A') = 'N/A'
            THEN 'N/A'
            ELSE TO_CHAR(RESTAN1 + RESTAN2 + RESTAN3)
        END as \"Saldo Taksasi Restan (ton)\",
        'INTEGER||| ||| ||| |||' as STYLE13,
        JML_JJG_RESTAN_SALDO as \"Saldo Taksasi Restan (jjg)\", 
        CASE
            WHEN NVL(TO_CHAR(RESTAN1),'N/A') = 'N/A'
            THEN 'DECIMAL|||#cccccc|||#ffffff||| |||'
            WHEN RESTAN1 = 0
            THEN 'DECIMAL|||#00b034|||#ffffff||| |||'
            ELSE 'DECIMAL|||#ff0000|||#ffffff||| |||'
        END as STYLE10,
        CASE
            WHEN NVL(TO_CHAR(RESTAN1),'N/A') = 'N/A'
            THEN 'N/A'
            ELSE TO_CHAR(RESTAN1)
        END as \"Restan Hari Ini (ton)\",
        'INTEGER||| ||| ||| |||' as STYLE14,
        JML_JJG_RESTAN_1 as \"Restan Hari Ini (jjg)\", 
        CASE
            WHEN NVL(TO_CHAR(RESTAN2),'N/A') = 'N/A'
            THEN 'DECIMAL|||#cccccc|||#ffffff||| |||'
            WHEN RESTAN2 = 0
            THEN 'DECIMAL|||#00b034|||#ffffff||| |||'
            ELSE 'DECIMAL|||#ff0000|||#ffffff||| |||'
        END as STYLE11,
        CASE
            WHEN NVL(TO_CHAR(RESTAN2),'N/A') = 'N/A'
            THEN 'N/A'
            ELSE TO_CHAR(RESTAN2)
        END as \"Restan 2 Hari (ton)\",
        CASE
            WHEN NVL(TO_CHAR(RESTAN3),'N/A') = 'N/A'
            THEN 'DECIMAL|||#cccccc|||#ffffff||| |||'
            WHEN RESTAN3 = 0
            THEN 'DECIMAL|||#00b034|||#ffffff||| |||'
            ELSE 'DECIMAL|||#ff0000|||#ffffff||| |||'
        END as STYLE12,
        CASE
            WHEN NVL(TO_CHAR(RESTAN3),'N/A') = 'N/A'
            THEN 'N/A'
            ELSE TO_CHAR(RESTAN3)
        END as \"Restan 3 - 15 Hari (ton)\"
FROM (
    SELECT    BCC.TGL_REPORT, 
            BCC.COMP_CODE, 
            BCC.TAKSASI_PANEN, 
            BCC.PERSEN_BRONDOLAN,
            BCC.TAKSASI_KIRIM,
            NVL(MILL.BERAT_TERIMA_MILL,0) as BERAT_TERIMA_MILL,
            BCC.RESTAN1,
            BCC.RESTAN2,
            BCC.RESTAN3,
            BCC.JML_JJG_RESTAN_SALDO,
            BCC.JML_JJG_RESTAN_1
    FROM (
        SELECT     TGL_REPORT, 
                SUBSTR(KODE_BA,1,2) as COMP_CODE,
                (NVL(SUM(TAKSASI_PANEN),0) / 1000) as TAKSASI_PANEN, 
                (NVL((SUM(BRONDOLAN) / NULLIF(SUM(TAKSASI_PANEN),0) * 100),0)) as PERSEN_BRONDOLAN,
                (NVL(SUM(TAKSASI_KIRIM),0) / 1000) as TAKSASI_KIRIM,
                (NVL(SUM(RESTAN1),0) /1000) as RESTAN1,
                (NVL(SUM(RESTAN2),0) /1000) as RESTAN2,
                (NVL(SUM(RESTAN3),0) /1000) as RESTAN3,
                (NVL(SUM(JML_JJG_RESTAN_SALDO),0)) as JML_JJG_RESTAN_SALDO,
                (NVL(SUM(JML_JJG_RESTAN_1),0)) as JML_JJG_RESTAN_1
        FROM (
            SELECT     TRUNC(SYSDATE-1) as TGL_REPORT, 
                    SUBSTR(MS_BLOCK.ID_BA_AFD,1,4) KODE_BA, 
                    T3.EST_NAME BUSSINES_AREA, 
                    SUBSTR(MS_BLOCK.ID_BA_AFD,5,1) AFDELING, 
                    MS_BLOCK.ID_BLOK BLOCK_CODE,
                    NVL(T1.JML_BRD_PANEN,0) BRONDOLAN,
                    NVL(T4.BJR_PREV,0) BJR_PREV,
                    ( NVL(T1.JML_JJG_PANEN,0) * NVL(T4.BJR_PREV,0) ) TAKSASI_PANEN,
                    ( NVL(T1.JML_JJG_ANGKUT,0) * NVL(T4.BJR_PREV,0) ) TAKSASI_KIRIM, 
            (
                SELECT NVL(SUM(T2A.KG_TAKSASI),0)
                FROM TAP_DW.TR_HV_RESTANT_DETAIL@DWH_LINK T2A
                WHERE T2A.NATIONAL=T1.NATIONAL
                AND T2A.REGION_CODE=T1.REGION_CODE
                AND T2A.COMP_CODE=T1.COMP_CODE
                AND T2A.EST_CODE=T1.EST_CODE
                AND T2A.WERKS=T1.WERKS
                AND T2A.AFD_CODE=T1.AFD_CODE
                AND T2A.BLOCK_CODE=T1.BLOCK_CODE
                AND T2A.BLOCK_CODE_GIS=T1.BLOCK_CODE_GIS
                AND T2A.TGL_REPORT=T1.TGL_REPORT
                AND T2A.SUB_BLOCK_CODE=T1.SUB_BLOCK_CODE
                AND T2A.TPH_RESTANT_DAY = 1
                --AND T2A.FLAG_NAB = 'NAB ADA'
            ) RESTAN1,
            (
                SELECT NVL(SUM(T2B.KG_TAKSASI),0)
                FROM TAP_DW.TR_HV_RESTANT_DETAIL@DWH_LINK T2B
                WHERE T2B.NATIONAL=T1.NATIONAL
                AND T2B.REGION_CODE=T1.REGION_CODE
                AND T2B.COMP_CODE=T1.COMP_CODE
                AND T2B.EST_CODE=T1.EST_CODE
                AND T2B.WERKS=T1.WERKS
                AND T2B.AFD_CODE=T1.AFD_CODE
                AND T2B.BLOCK_CODE=T1.BLOCK_CODE
                AND T2B.BLOCK_CODE_GIS=T1.BLOCK_CODE_GIS
                AND T2B.TGL_REPORT=T1.TGL_REPORT
                AND T2B.SUB_BLOCK_CODE=T1.SUB_BLOCK_CODE
                AND T2B.TPH_RESTANT_DAY = 2
                --AND T2B.FLAG_NAB = 'NAB ADA'
            ) RESTAN2,
            (
                SELECT NVL(SUM(T2C.KG_TAKSASI),0)
                FROM TAP_DW.TR_HV_RESTANT_DETAIL@DWH_LINK T2C
                WHERE T2C.NATIONAL=T1.NATIONAL
                AND T2C.REGION_CODE=T1.REGION_CODE
                AND T2C.COMP_CODE=T1.COMP_CODE
                AND T2C.EST_CODE=T1.EST_CODE
                AND T2C.WERKS=T1.WERKS
                AND T2C.AFD_CODE=T1.AFD_CODE
                AND T2C.BLOCK_CODE=T1.BLOCK_CODE
                AND T2C.BLOCK_CODE_GIS=T1.BLOCK_CODE_GIS
                AND T2C.TGL_REPORT=T1.TGL_REPORT
                AND T2C.SUB_BLOCK_CODE=T1.SUB_BLOCK_CODE
                AND T2C.TPH_RESTANT_DAY BETWEEN 3 AND 15
                --AND T2C.FLAG_NAB = 'NAB ADA'
            ) RESTAN3,
            (
                SELECT NVL(SUM(T2D.JML_JJG),0)
                FROM TAP_DW.TR_HV_RESTANT_DETAIL@DWH_LINK T2D
                WHERE T2D.NATIONAL=T1.NATIONAL
                AND T2D.REGION_CODE=T1.REGION_CODE
                AND T2D.COMP_CODE=T1.COMP_CODE
                AND T2D.EST_CODE=T1.EST_CODE
                AND T2D.WERKS=T1.WERKS
                AND T2D.AFD_CODE=T1.AFD_CODE
                AND T2D.BLOCK_CODE=T1.BLOCK_CODE
                AND T2D.BLOCK_CODE_GIS=T1.BLOCK_CODE_GIS
                AND T2D.TGL_REPORT=T1.TGL_REPORT
                AND T2D.SUB_BLOCK_CODE=T1.SUB_BLOCK_CODE
                AND T2D.TPH_RESTANT_DAY BETWEEN 1 AND 15
                --AND T2D.FLAG_NAB = 'NAB ADA'
            ) JML_JJG_RESTAN_SALDO,
            (
                SELECT NVL(SUM(T2E.JML_JJG),0)
                FROM TAP_DW.TR_HV_RESTANT_DETAIL@DWH_LINK T2E
                WHERE T2E.NATIONAL=T1.NATIONAL
                AND T2E.REGION_CODE=T1.REGION_CODE
                AND T2E.COMP_CODE=T1.COMP_CODE
                AND T2E.EST_CODE=T1.EST_CODE
                AND T2E.WERKS=T1.WERKS
                AND T2E.AFD_CODE=T1.AFD_CODE
                AND T2E.BLOCK_CODE=T1.BLOCK_CODE
                AND T2E.BLOCK_CODE_GIS=T1.BLOCK_CODE_GIS
                AND T2E.TGL_REPORT=T1.TGL_REPORT
                AND T2E.SUB_BLOCK_CODE=T1.SUB_BLOCK_CODE
                AND T2E.TPH_RESTANT_DAY = '1'
                --AND T2E.FLAG_NAB = 'NAB ADA'
            ) JML_JJG_RESTAN_1
            FROM EBCC.T_BLOK MS_BLOCK
            /* JOIN MS_BLOCK - T1 */
            LEFT JOIN TAP_DW.TR_HV_RESTANT_HEADER@DWH_LINK T1
            ON MS_BLOCK.ID_BA_AFD = T1.WERKS || T1.AFD_CODE
            AND MS_BLOCK.ID_BLOK = T1.BLOCK_CODE
            AND TO_CHAR(T1.TGL_REPORT,'YYYYMMDD')=TO_CHAR(SYSDATE-1,'YYYYMMDD')
            /* JOIN T1 - T3 */
            LEFT JOIN TAP_DW.TM_EST@DWH_LINK T3
            ON T3.WERKS = SUBSTR(MS_BLOCK.ID_BA_AFD,1,4)
            /* JOIN T1 - T4 */
            LEFT JOIN TAP_DW.TR_HV_BJR@DWH_LINK T4 
            ON T1.COMP_CODE=T4.COMP_CODE
            AND T1.EST_CODE=T4.EST_CODE
            AND T1.AFD_CODE=T4.AFD_CODE
            AND T1.SUB_BLOCK_CODE=T4.SUB_BLOCK_CODE
            AND TO_CHAR(T4.SPMON,'YYYYMM')=TO_CHAR(SYSDATE-1,'YYYYMM')
            /* PARAMETER */
            WHERE ( MS_BLOCK.INACTIVE_DATE IS NULL OR TRUNC(MS_BLOCK.INACTIVE_DATE) > TRUNC(SYSDATE-1) )
                AND SUBSTR(MS_BLOCK.ID_BA_AFD,1,2) = '21' -- FILTER PT
        )
        GROUP BY TGL_REPORT, SUBSTR(KODE_BA,1,2)
    ) BCC
    LEFT JOIN (
        SELECT    COMP_CODE,
                DATEOUT,
                SUM(BERAT_TERIMA_BLOCK) / 1000 as BERAT_TERIMA_MILL
        FROM (
            SELECT     BCC.*,
                    SWS.DATEOUT,
                    SWS.NETTOBEFOREGRADING,
                    SWS.FFB_UNITS,
                    ( BCC.ESTIMASI_BERAT / NULLIF(BCC.ESTIMASI_BERAT_PER_NAB,0) * SWS.NETTOBEFOREGRADING ) as BERAT_TERIMA_BLOCK
            FROM (
                SELECT     WBTICKET.*,
                        VENDOR.COMPANYCODE
                FROM (
                    SELECT COMPANYCODE as COMP_MILL, MILLPLANT, TICKETNO, DATEOUT, VENDORID, NETTOBEFOREGRADING, FFB_UNITS, SPBNO as SPB
                    FROM WB_STAGING.WB_PURCHASE
                    WHERE MATERIALID = '501010001'
                    AND DATEOUT = TO_CHAR(SYSDATE-1, 'RRRRMMDD')
                ) WBTICKET
                LEFT JOIN WB_STAGING.WB_VENDOR_PARAMETER VENDOR
                    ON TRIM(UPPER(WBTICKET.VENDORID)) = TRIM(UPPER(VENDOR.VENDORID))
                WHERE VENDOR.COMPANYCODE IS NOT NULL
            ) SWS
            LEFT JOIN (
                SELECT     BCC1.COMP_CODE,
                        BCC1.NO_NAB,
                        BCC1.WERKS,
                        BCC1.AFD_CODE,
                        BCC1.BLOCK_CODE,
                        BCC1.JJG_KIRIM,
                        BCC1.BJR,
                        BCC1.ESTIMASI_BERAT,
                        DATA_BCC_PER_NAB.ESTIMASI_BERAT_PER_NAB,
                        DATA_BCC_PER_NAB.JJG_KIRIM_PER_NAB
                FROM (        
                    SELECT    BCC.COMP_CODE,
                            BCC.NO_NAB,
                            BCC.WERKS,
                            BCC.AFD_CODE,
                            BCC.BLOCK_CODE,
                            SUM(BCC.JJG_KIRIM) as JJG_KIRIM,
                            ( MAX(BJR.BJR_PREV) ) as BJR,
                            ( SUM(BCC.JJG_KIRIM) * (MAX(BJR.BJR_PREV)) ) as ESTIMASI_BERAT
                    FROM (
                        SELECT    COMP_CODE,
                                NO_NAB,
                                WERKS,
                                AFD_CODE,
                                BLOCK_CODE,
                                TANGGAL_RENCANA,
                                SUM (DECODE (KETERANGAN, 'BUNCH_SEND', QTY, 0)) AS JJG_KIRIM
                        FROM (
                            SELECT     TBA.ID_CC as COMP_CODE,
                                    NAB.NO_NAB,
                                    TBA.ID_BA as WERKS,
                                    TA.ID_AFD as AFD_CODE,
                                    TB.ID_BLOK as BLOCK_CODE,
                                    THRP.TANGGAL_RENCANA,
                                    THK.ID_KUALITAS,
                                    THK.QTY,
                                    PARAM.KETERANGAN
                            FROM (
                                SELECT     ID_RENCANA,
                                        TANGGAL_RENCANA, 
                                        NIK_PEMANEN
                                FROM EBCC.T_HEADER_RENCANA_PANEN
                            )THRP
                            LEFT JOIN EBCC.T_DETAIL_RENCANA_PANEN TDRP
                                ON THRP.ID_RENCANA = TDRP.ID_RENCANA
                            LEFT JOIN EBCC.T_HASIL_PANEN THP
                                ON THP.ID_RENCANA = TDRP.ID_RENCANA
                                AND THP.NO_REKAP_BCC = TDRP.NO_REKAP_BCC
                            LEFT JOIN EBCC.T_HASILPANEN_KUALTAS THK    
                                ON THP.NO_BCC = THK.ID_BCC
                                AND THP.ID_RENCANA = THK.ID_RENCANA
                            LEFT JOIN EBCC.T_NAB NAB
                                ON NAB.ID_NAB_TGL = THP.ID_NAB_TGL
                            LEFT JOIN EBCC.T_BLOK TB
                                ON TDRP.ID_BA_AFD_BLOK = TB.ID_BA_AFD_BLOK
                            LEFT JOIN EBCC.T_AFDELING TA
                                ON TB.ID_BA_AFD = TA.ID_BA_AFD
                            LEFT JOIN EBCC.T_BUSSINESSAREA TBA
                                ON TBA.ID_BA = TA.ID_BA
                            LEFT JOIN (
                                SELECT BA_CODE, ID_KUALITAS, KETERANGAN
                                FROM EBCC.T_PARAMETER_BUNCH
                                WHERE KETERANGAN = 'BUNCH_SEND'
                            ) PARAM
                                ON THK.ID_KUALITAS = PARAM.ID_KUALITAS
                                AND TA.ID_BA = PARAM.BA_CODE
                            WHERE NAB.TGL_NAB BETWEEN TRUNC(SYSDATE-1-31) AND TRUNC(SYSDATE-1)
                        )
                        GROUP BY COMP_CODE,
                                NO_NAB,
                                WERKS,
                                AFD_CODE,
                                BLOCK_CODE,
                                TANGGAL_RENCANA
                    ) BCC
                    LEFT JOIN TAP_DW.TR_HV_BJR@DWH_LINK BJR 
                        ON BJR.COMP_CODE || BJR.EST_CODE = BCC.WERKS
                        AND BJR.AFD_CODE = BCC.AFD_CODE
                        AND BJR.SUB_BLOCK_CODE = BCC.BLOCK_CODE
                        AND TO_CHAR(BJR.SPMON,'RRRRMM') = TO_CHAR(BCC.TANGGAL_RENCANA, 'RRRRMM')
                    GROUP BY BCC.COMP_CODE,
                            BCC.NO_NAB,
                            BCC.WERKS,
                            BCC.AFD_CODE,
                            BCC.BLOCK_CODE    
                ) BCC1
                LEFT JOIN (
                    SELECT     COMP_CODE,
                            NO_NAB,
                            SUM(JJG_KIRIM) as JJG_KIRIM_PER_NAB,
                            SUM(ESTIMASI_BERAT) as ESTIMASI_BERAT_PER_NAB
                    FROM (
                        SELECT    BCC.COMP_CODE,
                                BCC.NO_NAB,
                                BCC.WERKS,
                                BCC.AFD_CODE,
                                BCC.BLOCK_CODE,
                                SUM(BCC.JJG_KIRIM) as JJG_KIRIM,
                                ( MAX(BJR.BJR_PREV) ) as BJR,
                                ( SUM(BCC.JJG_KIRIM) * (MAX(BJR.BJR_PREV)) ) as ESTIMASI_BERAT
                        FROM (
                            SELECT    COMP_CODE,
                                    NO_NAB,
                                    WERKS,
                                    AFD_CODE,
                                    BLOCK_CODE,
                                    TANGGAL_RENCANA,
                                    SUM (DECODE (KETERANGAN, 'BUNCH_SEND', QTY, 0)) AS JJG_KIRIM
                            FROM (
                                SELECT     TBA.ID_CC as COMP_CODE,
                                        NAB.NO_NAB,
                                        TBA.ID_BA as WERKS,
                                        TA.ID_AFD as AFD_CODE,
                                        TB.ID_BLOK as BLOCK_CODE,
                                        THRP.TANGGAL_RENCANA,
                                        THK.ID_KUALITAS,
                                        THK.QTY,
                                        PARAM.KETERANGAN
                                FROM (
                                    SELECT     ID_RENCANA,
                                            TANGGAL_RENCANA, 
                                            NIK_PEMANEN
                                    FROM EBCC.T_HEADER_RENCANA_PANEN
                                )THRP
                                LEFT JOIN EBCC.T_DETAIL_RENCANA_PANEN TDRP
                                    ON THRP.ID_RENCANA = TDRP.ID_RENCANA
                                LEFT JOIN EBCC.T_HASIL_PANEN THP
                                    ON THP.ID_RENCANA = TDRP.ID_RENCANA
                                    AND THP.NO_REKAP_BCC = TDRP.NO_REKAP_BCC
                                LEFT JOIN EBCC.T_HASILPANEN_KUALTAS THK    
                                    ON THP.NO_BCC = THK.ID_BCC
                                    AND THP.ID_RENCANA = THK.ID_RENCANA
                                LEFT JOIN EBCC.T_NAB NAB
                                    ON NAB.ID_NAB_TGL = THP.ID_NAB_TGL
                                LEFT JOIN EBCC.T_BLOK TB
                                    ON TDRP.ID_BA_AFD_BLOK = TB.ID_BA_AFD_BLOK
                                LEFT JOIN EBCC.T_AFDELING TA
                                    ON TB.ID_BA_AFD = TA.ID_BA_AFD
                                LEFT JOIN EBCC.T_BUSSINESSAREA TBA
                                    ON TBA.ID_BA = TA.ID_BA
                               LEFT JOIN (
                                    SELECT BA_CODE, ID_KUALITAS, KETERANGAN
                                    FROM EBCC.T_PARAMETER_BUNCH
                                    WHERE KETERANGAN = 'BUNCH_SEND'
                                ) PARAM
                                    ON THK.ID_KUALITAS = PARAM.ID_KUALITAS
                                    AND TA.ID_BA = PARAM.BA_CODE
                                WHERE NAB.TGL_NAB BETWEEN TRUNC(SYSDATE-1-31) AND TRUNC(SYSDATE-1)
                            )
                            GROUP BY COMP_CODE,
                                    NO_NAB,
                                    WERKS,
                                    AFD_CODE,
                                    BLOCK_CODE,
                                    TANGGAL_RENCANA
                        ) BCC
                        LEFT JOIN TAP_DW.TR_HV_BJR@DWH_LINK BJR 
                            ON BJR.COMP_CODE || BJR.EST_CODE = BCC.WERKS
                            AND BJR.AFD_CODE = BCC.AFD_CODE
                            AND BJR.SUB_BLOCK_CODE = BCC.BLOCK_CODE
                            AND TO_CHAR(BJR.SPMON,'RRRRMM') = TO_CHAR(BCC.TANGGAL_RENCANA, 'RRRRMM')
                        GROUP BY BCC.COMP_CODE,
                                BCC.NO_NAB,
                                BCC.WERKS,
                                BCC.AFD_CODE,
                                BCC.BLOCK_CODE
                    )
                    GROUP BY COMP_CODE,
                            NO_NAB
                ) DATA_BCC_PER_NAB
                    ON DATA_BCC_PER_NAB.COMP_CODE = BCC1.COMP_CODE
                    AND DATA_BCC_PER_NAB.NO_NAB = BCC1.NO_NAB
            ) BCC
                ON SWS.COMPANYCODE = BCC.COMP_CODE
                AND SWS.SPB = BCC.NO_NAB
        )
        GROUP BY COMP_CODE,
                DATEOUT
    ) MILL
        ON MILL.COMP_CODE = BCC.COMP_CODE
    ORDER BY BCC.COMP_CODE
)                                                                                        
";
	$resultPt = oci_parse($con, $sql);
	oci_execute($resultPt, OCI_DEFAULT);
	while(oci_fetch($resultPt)){
		echo oci_result($resultPt, "Business Area")."<br/>";
	}
	
	$ncols = oci_num_fields($resultPt);
	for ($i = 1; $i <= $ncols; $i++) {
    $column_name  = oci_field_name($stid, $i);
    $column_type  = oci_field_type($stid, $i);

	echo $column_name."</br>";
	
	}
	//oci_commit($con);
?>
