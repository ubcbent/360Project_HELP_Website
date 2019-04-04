**Project Info:** COSC 360 Project

**Due Dates:** See Milestone Dates

**Overview:**

The project is designed to help develop your skills for full stack development.  With this project, you will build an online application that will allow users to register, create and participate in different features of your site.  ***The project is s group project of 3 to four people.  This project can be done in conjunction with the COSC 304 project. Please note that the grading rubric for different aspects of the project per course may be different and are graded separately.**

**Hardware and Software:**

You will develop the project using Linux, MySQL, Apache and PHP on cosc360.ok.ubc.ca,in addition to CSS, HTML5 and JavaScript on the client-side.  **The backend must be completed with PHP.**  Your project will be stored in the class provided repository and will deployed to the cosc360 server.  The project must be accessible on the UBC network and all source code must be available for review.  You are required to keep your code under version control with GIT.

**MyAmazon Online Store**

The MyAmazon online store will allow users to buy goods on the web site. You decide if your company has physical stores and warehouses, and the products that it sells.

**Project Statement:**

To build a web-based store that that allows users engage in activities permitted for registered users and un-registered users.  Registered users will be allowed to buy products online and manages inventory and shipping information from the stores. Registered used will be able to track the status of their orders, search and browse for items to buy, and feel confident and secure in the purchasing process.   Registered users will be able to access specific portions/features of your site that are not accessible to unregistered users.  Registered users will be able to comment on specific items as well as browse comments.  Unregistered users will be able to browse the catalog, review comments and add items to a cart, but before processing the order, the account must be converted to a registered user.  Unregistered users will not be able to comment on items.  State must be maintained in terms of the cart, order history and login/logoff status.   If a user has items in the cart, logs off and then logs back in, the cart must be persistent.  Proper practices must be put in place to ensure that data is not leaked when users log on/log off.  The system must also support internal use by administrators who can track the volume and type of goods sold, add/remove items from the store, handle customer problems (such as forgotten passwords), manage product inventory levels, and generate sales reports.  Administrators must have separate accounts and be accessed through the standard logon portal

A first goal is to create the layout for the site.  The layout is to be a 2 or 3-column layout with navigation links along the top.  **No styling frameworks are not permitted as you are to develop the styling for the page using CSS.**  The page requires a masthead as well as a footer.  The navigation links need to be available regardless of the where a user is viewing the page.    You will need to think about the required of what pages look like based on the needs of an online store.

Pages will need to be created for user registration that allows for the entry of user information along with user image.  The form will need to be validated before being submitted using the appropriate technology.  

The system must also support internal use by administrators who can track features of the page and moderate issues, resolve user problems (such as forgotten passwords), and generate usage reports.

**Project Objectives:** The objectives are divided into two categories. The first category is the minimal requirements for the project to get a passing grade (C). The other requirements are some of the additional options that can be added to create an improved project. These objectives are further divided based on the implementation of different functional components utilizing different technologies.

**Baseline Objectives:**

Website user’s objectives:

*Browse items without registering
*Add/remote items from cart
*Search for items/posts by keyword without registering
*Register at the site by providing their name, e-mail and image
*Allow user login by providing user id and password
*Create and comment on (specific for each project) when logged into the site
*Users are required to be able to view/edit their profile
*User password recovery (via email)

Website administrator’s objectives:

*Search for user by name, email or post
*Enable/disable users
*Edit/remove items and comments

 As this project is about demonstrating and applying different web technologies, you will utilize different technologies in the construction of the site.
 
**Minimum Functional Requirements:**

* Hand-styled layout with contextual menus (i.e. when user has logged on to site, menus reflect change). Layout frameworks are not permitted.
* 2 or 3 column layout using appropriate design principles (i.e. highlighting nav links when hovered over, etc) responsive design
* Form validation with JavaScript
* Server-side scripting with PHP
* Data storage in MySQL
* Appropriate security for data
* Site must maintain state (user state being logged on, etc)
* Responsive design philosophy (minimum requirements for different non-mobile display sizes)
* AJAX (or similar) utilization for asynchronous updates (meaning that if a discussion thread is updated, another user who is viewing the same thread will not have to refresh the page to see the update), cart to update without page refresh
* User images (thumbnail) and profile stored in database
* Simple discussion (topics) grouping and display
* Navigation breadcrumb strategy (i.e. user can determine where they are in threads)
* Error handling (bad navigation)

**Additional Requirements:**

* Search and analysis for items
* Hot threads/hot item tracking
* Visual display of updates, etc (site usage charts, etc)
* Activity by date
* Tracking (including utilizing tracking API or your own with visualization tools)
* Collapsible items/treads without page reloading
* Alerts on page changes
* Admin view reports on usage (with filtering)
* Styling flourishes
* Tracking comment history from a user’s perspective
* Accessibility
* Your choice (this is your opportunity to add additional flourish’s to your site but will need to be documented in the final report)
Additional allowable technologies:

In addition to the core CSS3, PHP, HTML5 and JavaScript technologies, Bootstrap js and JQuery are permitted to be used. 

**Deliverables:**

This project should demonstrate your knowledge in full stack web design and programming. Your final submission will be submitted both electronically in the provided repository in addition to the site being available on cosc360.ok.ubc.ca. All your code and related files should be submitted in a zip file that is based on the correct file structure for the site.

**Milestones:**

**Friday, September 28th, 2018 – 0% (Proposal) – or sooner**

* Team member selections
* Project description and details:
  * Provide a description of the project you are going to undertake
  * Requirements list of what (at a minimum) your site will do (you will need to explore existing sites to understand their functional offerings). You should be able to understand from reading this document exactly what a user/administrator will be able to do on the site.  You will receive feedback on this so that you can direct the efforts of your project accordingly.
  * This will form a list of minimum end deliverables on the project and should be a comprehensive document.
  * Early review is encouraged for feedback.

**Friday, October 26th, 2018 – 40% (Client-side experience)**

* Layout document (Planned layout of your page in hardcopy/electronic copy showing elements, sizes, placement – this is the plan for what your site will look like)
* Organization of pages (How are pages linked? – site map)
* Logic process (How will a user engage with site?): This needs to include all processes for how the user/admin will engage site.
* Static design and styles of all pages
* Examples of each page type (coded with ipsum lorem as a minimum)
* Client-side validation
* Client-side security
* Static pages deployed

**Friday, November 30th, 2018 – 60% (Site should have minimal core functionality)**

* Server-side implementation complete
* Posted on cosc360.ok.ubc.ca (or cosc304.ok.ubc.ca)
* Server-side security
* User account information stored in database
* Items and associated details stored in database
* Cart maintained in database
* Asynchronous updates for cart and comments
* Database functionality complete
* Core functional components operational (see baseline objectives)
* Preliminary summary document, indicating implemented functionality
* Final delivery of site with additional functionality
* Summary of features implemented
* A 2-3 page walkthrough document that can be used to test the site by performing the walkthrough you describe.  It is to your advantage to include sufficient detail to highlight the best features of your website. This should also include things like **required login ids and passwords**, how to test your site as well as identifying any unique features.  **This document will be used as a guide to test what you did. This document should be written as a user guide.**
* A 2-3 page detailed description of your implementation from a system or developer's perspective including: What features did you implement? Include a description of the PHP and JavaScript files of your web site. How does your web site work at a high-level? Identify known limitations of the site? 
* 10% is reserved for deployment, version control, client and server side unit testing (if you do not test or deploy the maximum you can get out of this section is 50/60).

**Comments:**

You are welcome to submit before the deadlines.  The milestones are in place to indicate what will be evaluated at these points (ie. Layout, client-side validation, and security will be evaluated against the basic functional requirements) but you can improve with additional functionality which will be evaluated at final submission.

This project is not intended to be a complex project (in terms of the content and number of pages) but is intended to provide the opportunity to develop and showcase your full-stack skills.  In the development of the project, focus on key functional objectives and work through them in a planned fashion.  A simple, functional and well organized side is acceptable as long as it contains the required functionality utilizing the appropriate technology.   Please review the functional requirements to ensure your design satisfies the requirements.

You may request a project different than the online store. An alternate project must contain all the required components. The project may have a real-world sponsor, but that is not required. Your project must have the same deliverables as the standard project.
