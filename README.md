<div id="header" align="center">
<img src="https://media.giphy.com/media/KiZ6kV683kPaU/giphy.gif" width="300"/>
</div>

# Database
- Use the new database for experimenting
- If you want to modify columns, please reimport and modify then only export

# Changes Log
05/02/25 5.49pm [Goe Jie Ying]
- Add some notification in view_member_list.php

05/02/25 1.53am [Goe Jie Ying]
- Done for member batch approval
- Added search bar for all list

04/02/25 11:03pm [Lam Yoke Yu]
- Updated for potongan_gaji
- Hopefully completed

04/02/25 5:44pm [Lam Yoke Yu]
- Conflicting column in simpanan tetap and simpanan anggota / wang deposit anggota
- Updated update_profile -> potongan_gaji

04/02/25 2.13pm [Goe Jie Ying]
- Batch Approval for Berhenti Member and Loan Approval
- Edit the berhenti_status to display only the member's information
- header admin updated

04/02/25 11:02am [Lam Yoke Yu]
- Updated kemaskini_polisi with additional fields
- UI in the Kemaskini Polisi Potongan Gaji still need fixes
- Bug fixes in sejarah_polisi.php potongan gaji card
- Minor adjustment in a_pinjaman.php

26/01/25 5.43pm [Goe Jie Ying]
- Added new list (past member)
- Added edit mode in member list (pencen/berhenti)

26/01/25 4.56pm [Teh Ru Qian]
- Update a_pinjaman.php (add status ahli, pencen and berhenti cannot apply for loan)

26/01/25 3.19am [Tan Yi Ya]
- added functionality to register as a returning member
- auto fill informations by past data and feemasuk of rm100

25/01/25 11.08pm [Lam Yoke Yu]
- Add Policy History
- Update header admin

25/01/25 4.04pm [Chua Jia Lin]
- Update berhenti

25/01/25 3.45pm [Teh Ru Qian]
- Update generate_pdf.php (add status ahli)
- Update dashboardLaporan.php (add status ahli)
- Update a_pinjaman.php (done follow all maxloan and rate)
- Changed laporan name
- Still have bug for edit pinjaman (draft)

25/01/25 3.25pm [Lam Yoke Yu]
- Added filters to potongan_gaji
- Added fields for receipt number and upload proof in transaksi_lain

25/01/25 3.17pm [Lam Yoke Yu]
- Update Database (db_kkk 25.01 3.16pm)
- Added columns in tb_transaction for receipt number and proof

25/01/25 2.39am [Goe Jie Ying]
- Update Berhenti_approval_detail and process, berhenti_approval interface haven't finalise yet
- Update Database (db_kkk (12).sql), specifically in tb_tarikdiri (a new column added)
- Added search bar (works) and editing mode (for AA, not function well) in member list

24/01/24 11.07pm [Tan Yi Ya]
- Archives are useless for now. Trying to create livechat module that connects admin and member
- created customer support interface. try it at http://127.0.0.1/KKK-System/chatbot/
- will update database once completed livechat module
<img src="https://github.com/user-attachments/assets/fc7f4ad7-f0e3-4004-8bbb-afee65a9838a" width="100" />

24/01/25 10.29pm [Lam Yoke Yu]
- Update kemaskini_polisi
- Want to do a checkbox for condition if same interest rate then merge rows but failed

24/01/25 6.50pm [Goe Jie Ying]
Added active in header_admin, the old version still with me, if tak suka the new boleh tukar balik
p/s: dunno why the item on nav bar cannot be highlighted, just the things in dropdown list can

24/01/25 5.42pm [Chua Jia Lin]
- Sweet alert

24/01/25 1.13pm [Chua Jia Lin]
- Edit interfaces for feedback
- add column fb_editStatusDate in tb_feedback (db_kkk (11).sql)
  
24/01/25 1.41am [Chua Jia Lin]
- Done full feedback function
  
23/01/25 10.49pm [Goe Jie Ying]
- change tarik diri to berhenti

23/01/25 10.32pm [Chua Jia Lin]
- Done member_side feedback function
- change card content to middle of the card title in view record and track_feedback file

23/01/25 9.09pm [Lam Yoke Yu]
- New Database

23/01/25 8.30pm [Lam Yoke Yu]
- Delete old databases
- Transaction history feature 

23/01/25 7.32pm [Goe Jie Ying]
- change footer information

23/01/25 6.35pm [Chua Jia Lin]
- add interface for feedback & tarik diri for member
- create new table tb_feedback,tb_fbtype,tb_tarikdiri

23/01/25 [Name]
- Buat Apa
