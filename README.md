# **College Complaint Management System (CCMS)**

### **System Overview & Guideline**

---

## **1. Introduction**

The **College Complaint Management System (CCMS)** is a web-based platform designed to streamline the reporting, tracking, and resolution of maintenance and facility-related issues within a college environment.
It replaces manual reporting with a centralized digital workflow that promotes **accountability**, **transparency**, and **efficiency** between Students, Staff, and Administrators.

---

## **2. User Roles & Access Control**

The system consists of **three user roles**, each with defined permissions.

---

### **A. Student (End User)**

**Access:** Public-facing frontend
**Primary Goal:** Report issues & track status updates

**Capabilities:**

* Self-registration (Student ID as unique ID)
* Submit complaints with description & photos
* **AI-Assisted Reporting:** Auto-generate descriptions from uploaded images
* View complaint status (Open → In Progress → Resolved → Closed)
* Withdraw complaints (when still "Open")
* Manage profile and credentials
* Submit general feedback

---

### **B. Staff (Operational User)**

**Access:** Admin panel (Restricted)
**Primary Goal:** Resolve assigned complaints

**Capabilities:**

* View complaints relevant to their Department
* Update complaint status (In Progress → Resolved)
* Auto-assignment: First staff to update a ticket becomes the assignee
* View student feedback
* Manage personal profile

---

### **C. Administrator (Super User)**

**Access:** Admin panel (Full access)
**Primary Goal:** System oversight & management

**Capabilities:**

* Manage master data (Students, Staff, Department, Dorm, Categories)
* View and control all complaints
* Manually assign/reassign complaints
* Override statuses (can manually close tickets)
* Edit system settings (Logo, Contact Info, System Title)
* Generate PDF reports

---

## **3. Key Features & Workflows**

---

### **3.1 Complaint Lifecycle**

1. **Open** – Student submits the complaint
2. **In Progress** – Staff acknowledges the issue

   * Auto-assigns the complaint to the staff
3. **Resolved** – Staff completes the work
4. **Closed**

   * Auto-close after 7 days of “Resolved”
   * Admin may manually close
5. **Withdrawn** – Student cancels (only if still Open)

---

### **3.2 Department-Based Routing**

* Each **Category** is linked to a **Department**
* Staff can only see complaints from their Department
* Ensures proper routing (e.g., Electrical does not see Plumbing tickets)

---

### **3.3 Validation & Security**

* Student ID format is validated and used as **Primary Key** (cannot be changed)
* Registration restricted to official **@college.edu** domains
* Public status lookup hides sensitive info (Name, Room No.)

---

### **3.4 AI-Assisted Reporting (Pending Feature)**

**Process:**

1. Student uploads an issue photo (JPEG/PNG)
2. System sends the image to an AI Vision model
3. AI returns a detailed issue description
4. Description auto-fills the text box
5. Student reviews and edits before final submission

---

## **4. System Architecture (Technical Overview)**

---

### **Database Structure (MySQL)**

| Table                            | Description                           |
| -------------------------------- | ------------------------------------- |
| **student** (PK: student_id)     | Student profiles                      |
| **staff** (PK: staff_id)         | Staff & admin profiles                |
| **complaint** (PK: complaint_id) | Main complaint records                |
| **categories**                   | Complaint types linked to Departments |
| **department**                   | Master list of departments            |
| **dormitory**                    | Master list of dormitories            |

---

### **Code Structure**

```
/
├── index.php                 # Student dashboard
├── complaints.php            # Complaint listing (student)
├── submit_complaint.php      # Complaint submission form
│
├── admin/                    # Admin Panel
│   ├── dashboard.php
│   ├── complaint.php
│   ├── settings.php
│   └── assets/js/include/    # JS logic (AJAX, validation, UI)
│       ├── main.js
│       ├── homejs.js
│       └── add.js
│
└── server/
    ├── api.php               # API Router for AJAX
    └── inc/
        ├── connection.php    # DB connection
        ├── get.php           # SELECT queries
        ├── add.php           # INSERT queries
        ├── update.php        # UPDATE queries
        └── delete.php        # DELETE functions
```

---

## **5. How to Navigate the Code**

| Task                        | File/Folder                                |
| --------------------------- | ------------------------------------------ |
| Modify forms                | `/admin/assets/js/include/` (e.g., add.js) |
| Change database queries     | `/server/inc/get.php`, `update.php`, etc.  |
| Update UI layout            | PHP files in `/admin/` or root folder      |
| Change global site settings | Through `admin/settings.php` (recommended) |

---
