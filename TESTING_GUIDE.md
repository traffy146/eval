# Testing Guide - Subject Auto-Assignment Feature

## Quick Start Testing

### Step 1: Run Database Migration
1. Open phpMyAdmin
2. Select `evaluation_db` database
3. Go to SQL tab
4. Open and run: `database/complete_migration.sql`
5. Verify tables created:
   - `subject_course_mapping`
   - `irregular_student_subjects`
   - `activity_logs`
6. Verify `student_list` has `is_irregular` column

---

## Step 2: Create Subject-Course Mappings

### Example Setup for BSIT Program:

**Year 1 Subjects:**
1. Login as Admin
2. Go to "Subject-Course Mapping" menu
3. For each Year 1 subject (e.g., Programming 1, Math 1):
   - Select subject from dropdown
   - Curriculum: BSIT
   - Level: 1
   - Click "Add Mapping"

**Year 2 Subjects:**
Repeat for Year 2 subjects with Level: 2

**Year 3 Subjects:**
Repeat for Year 3 subjects with Level: 3

**Year 4 Subjects:**
Repeat for Year 4 subjects with Level: 4

Repeat for other programs (BSAIS, etc.)

---

## Step 3: Test Auto-Assignment

### Test Case 1: Regular Student - Individual Update
1. Go to Student List
2. Edit a student currently in BSIT Year 1
3. Change their class to BSIT Year 2
4. Click Save
5. **Verify:**
   - Go to Restriction List
   - Filter by this student
   - Should show ONLY Year 2 subjects
   - Old Year 1 subjects should be gone
   - Faculty ID should be 1 (placeholder)

### Test Case 2: Bulk Update
1. Go to Student List
2. Filter: Course = BSIT, Year = 1
3. Select all students (checkboxes)
4. Bulk Update panel: Select BSIT Year 2 class
5. Click "Update Selected Students"
6. **Verify:**
   - All students now in Year 2 class
   - Check restriction list for each
   - Should have Year 2 subjects assigned

### Test Case 3: Irregular Student
1. Create or edit a student
2. Check "Irregular Student" checkbox
3. Save student
4. Go to "Manage Irregular Students"
5. Select the irregular student
6. Add extra subjects (e.g., a Year 3 subject)
7. **Verify:**
   - Student can have subjects from different years
   - Subjects are added to irregular_student_subjects table
8. Now change student's class to different year
9. **Verify:**
   - Irregular subjects are preserved
   - New subjects are added based on new class

---

## Step 4: Test Complete Workflow

### Scenario: Moving students to next year
1. **Setup:** Create subject mappings for Year 1 and Year 2
2. **Create student:** John Doe, BSIT Year 1
3. **Verify:** John has Year 1 subjects auto-assigned
4. **Advance student:** Change John to BSIT Year 2
5. **Verify:** 
   - John's old Year 1 subjects removed
   - John's new Year 2 subjects assigned
6. **Assign faculty:**
   - Go to Restriction List
   - Find John's Year 2 subjects
   - Edit each restriction to assign actual faculty
   - Change faculty_id from 1 to actual teacher ID

### Scenario: Irregular student needs extra subjects
1. **Setup:** Student Mary, BSIT Year 2, mark as irregular
2. **Add subjects:** 
   - Go to Manage Irregular Students
   - Select Mary
   - Add subjects she needs (maybe a Year 1 subject she failed)
3. **Verify:**
   - Mary has Year 2 subjects (from class)
   - Mary has additional irregular subjects
   - Total subjects = normal + irregular
4. **Advance year:**
   - Change Mary to Year 3
   - Irregular subjects should remain
   - Year 3 subjects should be added

---

## Common Issues & Solutions

### Issue: No subjects assigned after class change
**Check:**
- Are there subject-course mappings for that course/year?
- Go to Subject-Course Mapping and verify
- Add mappings if missing

### Issue: Old subjects not removed
**Check:**
- Is student marked as irregular?
- If irregular, this is expected behavior
- If not irregular, check class_id in database

### Issue: Placeholder faculty_id = 1
**This is normal:**
- System assigns placeholder ID
- Admin must manually assign actual faculty
- Go to Restriction List to assign teachers

### Issue: Irregular subjects deleted
**Check:**
- Student should be marked as irregular
- Check is_irregular = 1 in student_list table
- Re-add subjects via Manage Irregular Students

---

## Database Verification Queries

### Check subject-course mappings:
```sql
SELECT s.code, s.subject, scm.curriculum, scm.level
FROM subject_course_mapping scm
JOIN subject_list s ON scm.subject_id = s.id
ORDER BY scm.curriculum, scm.level, s.code;
```

### Check auto-assigned subjects for a student:
```sql
SELECT st.firstname, st.lastname, s.code, s.subject, r.faculty_id
FROM restriction_list r
JOIN subject_list s ON r.subject_id = s.id
JOIN student_list st ON r.student_id = st.id
WHERE st.id = 1  -- Change to actual student ID
ORDER BY s.code;
```

### Check irregular student subjects:
```sql
SELECT st.firstname, st.lastname, s.code, s.subject
FROM irregular_student_subjects iss
JOIN subject_list s ON iss.subject_id = s.id
JOIN student_list st ON iss.student_id = st.id
WHERE st.is_irregular = 1
ORDER BY st.lastname, s.code;
```

### Check students without mappings:
```sql
SELECT st.firstname, st.lastname, c.curriculum, c.level
FROM student_list st
JOIN class_list c ON st.class_id = c.id
WHERE st.id NOT IN (
    SELECT DISTINCT student_id FROM restriction_list
)
ORDER BY c.curriculum, c.level, st.lastname;
```

---

## Expected Behavior Summary

| Action | Regular Student | Irregular Student |
|--------|----------------|-------------------|
| Create new student | Auto-assign subjects based on class | Auto-assign subjects based on class |
| Change class | Remove old subjects, assign new ones | Keep ALL old subjects, add new ones |
| Add irregular subject | N/A | Add subject without removing others |
| Bulk update class | Auto-assign new subjects | Keep irregular + add new subjects |

---

## Success Criteria

✅ **Subject-Course Mapping works:**
- Can create mappings
- Can view mappings
- Can delete mappings
- Filter works correctly

✅ **Auto-Assignment works:**
- New students get subjects automatically
- Changing class updates subjects
- Bulk update triggers auto-assignment
- Placeholder faculty_id = 1 is set

✅ **Irregular Student works:**
- Can mark student as irregular
- Can add extra subjects
- Extra subjects preserved on class change
- Regular subjects still auto-assigned

✅ **Integration works:**
- Works with existing restriction system
- Works with bulk update
- Works with student forms
- No errors in browser console

---

## Next Steps After Testing

1. **Assign Faculty:**
   - Use Restriction List to assign actual teachers
   - Change faculty_id from 1 to real teacher IDs

2. **Train Admin Staff:**
   - How to create subject-course mappings
   - How to handle irregular students
   - How to verify auto-assignments

3. **Monitor:**
   - Check activity_logs for errors
   - Verify students have correct subjects
   - Ensure evaluations work correctly

4. **Fine-tune:**
   - Add more subject-course mappings as needed
   - Adjust irregular student subjects
   - Update mappings when curriculum changes
