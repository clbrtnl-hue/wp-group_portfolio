<?php
/**
 * Team Portfolio standalone theme functions.
 * Provides the group portfolio and three individual portfolio layouts.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

define( 'TEAM_PORTFOLIO_VERSION', '1.4.0' );

add_action( 'wp_enqueue_scripts', function () {
    wp_enqueue_style( 'team-portfolio-style', get_stylesheet_uri(), array(), TEAM_PORTFOLIO_VERSION );
    wp_enqueue_style( 'team-portfolio-extra-style', get_theme_file_uri( 'assets/css/portfolio.css' ), array( 'team-portfolio-style' ), TEAM_PORTFOLIO_VERSION );
    wp_enqueue_script( 'team-portfolio-script', get_theme_file_uri( 'assets/js/portfolio.js' ), array(), TEAM_PORTFOLIO_VERSION, true );
} );

function tp_e( $value ) { return esc_html( $value ); }
function tp_url( $value ) { return esc_url( $value ); }

function tp_header( $active = 'Home', $member = false ) {
    $home = home_url( '/' );
    $links = $member
        ? array( 'Home'=>'#top', 'About Me'=>'#about', 'Education'=>'#education', 'Skills'=>'#skills', 'Projects'=>'#projects', 'Experience'=>'#experience', 'Achievements'=>'#achievements', 'Contact'=>'#contact' )
        : array( 'Home'=>'#top', 'About'=>'#about', 'Our Team'=>'#team', 'Skills'=>'#skills', 'Projects'=>'#projects', 'Our Journey'=>'#journey', 'Contact'=>'#contact' );
    ob_start(); ?>
    <header class="tp-header">
      <a class="tp-brand" href="<?php echo esc_url( $home ); ?>"><span class="tp-brand-mark">T</span><span><?php echo $member ? 'PERSONAL PORTFOLIO' : 'THE CREATIVE STACK'; ?></span></a>
      <button class="tp-menu-toggle" aria-label="Open navigation" aria-expanded="false"><span></span><span></span><span></span></button>
      <nav class="tp-nav" aria-label="Main navigation">
        <?php foreach ( $links as $label=>$href ) : ?><a href="<?php echo esc_url( $href ); ?>"><?php echo esc_html($label); ?></a><?php endforeach; ?>
        <button class="tp-theme-toggle" type="button" aria-label="Toggle light and dark theme">☼</button>
      </nav>
    </header>
    <?php return ob_get_clean();
}

function tp_footer( $member = false ) {
    ob_start(); ?>
    <footer class="tp-footer">
      <a class="tp-brand" href="<?php echo esc_url(home_url('/')); ?>"><span class="tp-brand-mark">T</span><span>THE CREATIVE STACK</span></a>
      <p><?php echo $member ? 'A personal portfolio within our team story.' : 'Three perspectives. One shared passion for technology.'; ?></p>
      <a href="<?php echo esc_url(home_url('/')); ?>">← Back to Group Portfolio</a>
      <small>© <?php echo esc_html( gmdate('Y') ); ?> The Creative Stack · Built with WordPress</small>
    </footer>
    <button class="tp-back-top" type="button" aria-label="Back to top">↑</button>
    <?php return ob_get_clean();
}

function tp_section_head( $eyebrow, $title, $copy = '' ) {
    return '<div class="tp-section-head"><span class="tp-eyebrow">'.tp_e($eyebrow).'</span><h2>'.tp_e($title).'</h2>'.( $copy ? '<p>'.tp_e($copy).'</p>' : '' ).'</div>';
}

function tp_placeholder_avatar( $initials, $class = '' ) {
    return '<div class="tp-avatar '.esc_attr($class).'" aria-label="Profile photo placeholder"><span>'.tp_e($initials).'</span><small>Add photo</small></div>';
}

function tp_group_portfolio_shortcode() {
    $members = array(
      array('name'=>'Chloe Lou D. Brotonel','slug'=>'chloe','initials'=>'CB','intro'=>'A developing IT professional with a story still being written.','focus'=>'Add verified technical interests','tagline'=>'Curious mind. Growing every day.','accent'=>'coral'),
      array('name'=>'John Bryan T. Mandario','slug'=>'john-bryan','initials'=>'JM','intro'=>'An IT student building skills through learning and hands-on projects.','focus'=>'Add verified technical interests','tagline'=>'Learn, build, improve.','accent'=>'blue'),
      array('name'=>'Keziyah Jarelle C. Lopez','slug'=>'keziyah','initials'=>'KL','intro'=>'A fourth-year BSIT student who enjoys creative IT work, learning new things, and figuring out how systems connect.','focus'=>'Networking · Information Management · Web projects','tagline'=>'Code with purpose. Grow with passion.','accent'=>'teal'),
    );
    ob_start(); ?>
    <div class="tp-site tp-group" id="top">
      <?php echo tp_header(); ?>
      <main>
        <section class="tp-hero tp-group-hero">
          <div class="tp-hero-grid"></div><div class="tp-hero-copy">
            <span class="tp-kicker"><i></i> STUDENT TEAM PORTFOLIO · BS INFORMATION TECHNOLOGY</span>
            <h1>Different minds.<br><em>One digital</em> journey.</h1>
            <p class="tp-lead">We are three IT students learning, creating, and growing through technology. This is where our individual stories meet our shared work.</p>
            <div class="tp-actions"><a class="tp-button tp-button-primary" href="#team">Meet our team <span>↗</span></a><a class="tp-button tp-button-ghost" href="#projects">Explore projects <span>↓</span></a></div>
            <div class="tp-hero-meta"><span>03 MEMBERS</span><span>01 SHARED JOURNEY</span><span>∞ IDEAS TO EXPLORE</span></div>
          </div>
          <div class="tp-hero-art" aria-label="Abstract team illustration"><div class="tp-orbit orbit-one"></div><div class="tp-orbit orbit-two"></div><div class="tp-art-core"><span>&lt;/&gt;</span></div><div class="tp-float tp-float-one">CREATE<span>✳</span></div><div class="tp-float tp-float-two">COLLABORATE<span>↗</span></div><div class="tp-art-caption">THE CREATIVE<br><b>STACK</b><small>2026 / PORTFOLIO</small></div></div>
          <span class="tp-hero-index">01 — 04</span>
        </section>

        <section class="tp-section tp-about" id="about">
          <?php echo tp_section_head('01 / WHO WE ARE','A team shaped by curiosity.','We are Bachelor of Science in Information Technology students bringing our different perspectives together to learn, solve problems, and create useful digital experiences.'); ?>
          <div class="tp-about-grid"><div class="tp-about-statement"><span class="tp-quote-mark">“</span><h3>Technology is more than what we study. It is a space where we can <em>create, connect,</em> and keep becoming better.</h3></div><div class="tp-about-details"><p>Our shared experience as IT students gives us a foundation in computer systems, information management, and digital technologies. We are interested in exploring how software and technology can respond to real needs.</p><p>We value open communication, shared responsibility, patience, and learning from one another. We believe good teamwork means listening, contributing, and being willing to improve together.</p><div class="tp-stat-row"><div><b>03</b><span>TEAM MEMBERS</span></div><div><b>BSIT</b><span>COURSE</span></div><div><b>∞</b><span>ROOM TO GROW</span></div></div></div></div>
        </section>

        <section class="tp-section tp-team-section" id="team">
          <?php echo tp_section_head('02 / THE PEOPLE','Meet the team.','Three individual paths, connected by a shared interest in technology. Select a profile to explore the person behind the work.'); ?>
          <div class="tp-member-grid">
          <?php foreach($members as $i=>$m): ?>
            <article class="tp-member-card <?php echo esc_attr($m['accent']); ?>">
              <a class="tp-member-link" href="<?php echo esc_url(home_url('/'.$m['slug'].'/')); ?>" aria-label="<?php echo esc_attr('View '.$m['name'].' portfolio'); ?>">
                <div class="tp-member-visual"><?php echo tp_placeholder_avatar($m['initials']); ?><span class="tp-card-number">0<?php echo (int)($i+1); ?></span><span class="tp-card-arrow">↗</span></div>
                <div class="tp-member-info"><span class="tp-eyebrow">BSIT STUDENT</span><h3><?php echo tp_e($m['name']); ?></h3><p><?php echo tp_e($m['intro']); ?></p><div class="tp-member-focus"><?php echo tp_e($m['focus']); ?></div><span class="tp-text-link">VIEW PORTFOLIO <b>↗</b></span></div>
              </a>
            </article>
          <?php endforeach; ?>
          </div>
        </section>

        <section class="tp-section tp-skills-section" id="skills">
          <?php echo tp_section_head('03 / WHAT WE BRING','Skills in progress.','Our work is a reflection of what we have studied and practiced. This list focuses on the tools confirmed in our shared project work.'); ?>
          <div class="tp-skill-grid">
            <div class="tp-skill-card"><span class="tp-skill-icon">⌘</span><h3>Web application development</h3><p>Building and improving web-based systems.</p><div class="tp-tags"><span>HTML</span><span>CSS</span><span>JavaScript</span><span>Vite</span></div></div>
            <div class="tp-skill-card"><span class="tp-skill-icon">▤</span><h3>Backend & data</h3><p>Application logic and structured data storage.</p><div class="tp-tags"><span>Laravel</span><span>MySQL</span></div></div>
            <div class="tp-skill-card"><span class="tp-skill-icon">✳</span><h3>Teamwork</h3><p>Sharing tasks, communicating, and learning through collaboration.</p><div class="tp-tags"><span>Collaboration</span><span>Communication</span><span>Problem-solving</span></div></div>
          </div>
          <p class="tp-note">Technologies shown are based on the team's documented project stack. Individual skill levels are not represented.</p>
        </section>

        <section class="tp-section tp-project-section" id="projects">
          <?php echo tp_section_head('04 / SELECTED WORK','Projects with a purpose.','A look at the work we have explored together. Project details and individual contributions can be expanded as the team confirms them.'); ?>
          <div class="tp-project-grid">
            <article class="tp-project-card"><div class="tp-project-cover cover-opcr"><span>CASE STUDY / 01</span><div class="tp-screen-ui"><i></i><i></i><i></i><b>PERFORMANCE<br>MONITORING</b><small>OPCR / IPCR</small></div></div><div class="tp-project-body"><div class="tp-project-meta"><span>WEB APPLICATION</span><span>01</span></div><h3>OPCR & IPCR Monitoring System</h3><p>A web-based system for managing performance commitments, accomplishments, supporting evidence, and review workflows at Opol Community College.</p><div class="tp-tags"><span>Laravel</span><span>MySQL</span><span>Vite</span></div><button class="tp-text-link tp-modal-trigger" data-modal="project-opcr">VIEW PROJECT DETAILS ↗</button></div></article>
            <article class="tp-project-card"><div class="tp-project-cover cover-hotel"><span>ACADEMIC PROJECT / 02</span><div class="tp-screen-ui"><b>HOTEL<br>MANAGEMENT</b><small>RESERVATIONS · RECORDS</small></div></div><div class="tp-project-body"><div class="tp-project-meta"><span>MANAGEMENT SYSTEM</span><span>02</span></div><h3>Hotel Management System</h3><p>A project concept focused on organizing hotel-related information and common management tasks. Add the confirmed problem, features, and implementation details.</p><div class="tp-tags"><span>Academic Project</span><span>Details to confirm</span></div><button class="tp-text-link tp-modal-trigger" data-modal="project-hotel">VIEW PROJECT DETAILS ↗</button></div></article>
            <article class="tp-project-card"><div class="tp-project-cover cover-quiz"><span>ACADEMIC PROJECT / 03</span><div class="tp-screen-ui"><b>QUIZ<br>GAME</b><small>QUESTIONS · SCORE</small></div></div><div class="tp-project-body"><div class="tp-project-meta"><span>INTERACTIVE APPLICATION</span><span>03</span></div><h3>Quiz Game</h3><p>An interactive quiz game project. Add the confirmed subject, gameplay rules, scoring approach, features, and technologies used.</p><div class="tp-tags"><span>Academic Project</span><span>Details to confirm</span></div><button class="tp-text-link tp-modal-trigger" data-modal="project-quiz">VIEW PROJECT DETAILS ↗</button></div></article>
          </div>
        </section>

        <section class="tp-section tp-journey" id="journey">
          <?php echo tp_section_head('05 / OUR JOURNEY','Still learning. Still becoming.','Our journey is ongoing. These milestones describe our shared direction without assuming dates or achievements that have not yet been confirmed.'); ?>
          <div class="tp-timeline"><div class="tp-timeline-item"><span class="tp-timeline-dot"></span><small>THE BEGINNING</small><h3>Learning the foundations</h3><p>Building our knowledge as BSIT students and discovering the areas of technology we want to explore.</p></div><div class="tp-timeline-item"><span class="tp-timeline-dot"></span><small>WORKING TOGETHER</small><h3>Turning lessons into projects</h3><p>Applying what we learn through collaborative academic work and a shared system development project.</p></div><div class="tp-timeline-item"><span class="tp-timeline-dot"></span><small>WHAT'S NEXT</small><h3>Growing beyond the classroom</h3><p>Continuing to improve our skills, document our experiences, and prepare for opportunities ahead.</p></div></div>
        </section>

        <section class="tp-section tp-contact" id="contact">
          <div class="tp-contact-panel"><div><span class="tp-eyebrow">06 / LET'S CONNECT</span><h2>Have a project<br>in mind?</h2><p>We would love to hear from you. Send a message and let us know what you have in mind.</p><a class="tp-button tp-button-primary" href="mailto:team@example.com">Email the team ↗</a><small>Replace team@example.com with your group's contact email.</small></div>
          <form class="tp-contact-form" data-contact-form data-recipient="team@example.com"><label>Your name<input name="name" autocomplete="name" required placeholder="Your name"></label><label>Email address<input name="email" type="email" autocomplete="email" required placeholder="you@example.com"></label><label>Subject<input name="subject" required placeholder="What is this about?"></label><label>Message<textarea name="message" rows="4" required placeholder="Write your message..."></textarea></label><button class="tp-button tp-button-primary" type="submit">Prepare message ↗</button><p class="tp-form-note" aria-live="polite">This form opens your email app. Add a real group email address to activate it.</p></form></div>
        </section>
      </main>
      <div class="tp-modal" id="project-opcr" aria-hidden="true" role="dialog" aria-modal="true" aria-labelledby="opcr-title"><div class="tp-modal-backdrop" data-close-modal></div><div class="tp-modal-panel"><button class="tp-modal-close" data-close-modal aria-label="Close project details">×</button><span class="tp-eyebrow">WEB APPLICATION / ACADEMIC PROJECT</span><h2 id="opcr-title">OPCR & IPCR Monitoring System</h2><p>A web-based performance monitoring and reporting system developed for Opol Community College. It supports the handling of Office Performance Commitment and Review (OPCR) and Individual Performance Commitment and Review (IPCR) records.</p><h3>Project focus</h3><ul><li>Organizing performance commitments and cascading targets.</li><li>Recording accomplishments and supporting evidence.</li><li>Supporting review, return, and rating workflows.</li><li>Presenting progress and status information.</li></ul><h3>Technology stack</h3><div class="tp-tags"><span>Laravel</span><span>MySQL</span><span>Vite</span><span>HTML</span><span>CSS</span><span>JavaScript</span></div><p class="tp-note">Add confirmed objectives, feature descriptions, development dates, repository/demo links, and each member's actual contribution before presenting this as a final project case study.</p></div></div>
      <div class="tp-modal" id="project-hotel" aria-hidden="true" role="dialog" aria-modal="true" aria-labelledby="hotel-title"><div class="tp-modal-backdrop" data-close-modal></div><div class="tp-modal-panel"><button class="tp-modal-close" data-close-modal aria-label="Close project details">×</button><span class="tp-eyebrow">ACADEMIC PROJECT / MANAGEMENT SYSTEM</span><h2 id="hotel-title">Hotel Management System</h2><p>This project is presented as a starter portfolio entry. Use this space to explain the hotel or front-desk process your system is intended to support and the specific issue it addresses.</p><h3>Project information to complete</h3><ul><li>Project objective and intended users.</li><li>Confirmed modules, such as room, guest, or reservation records, only if included in your actual build.</li><li>Tools and technologies used.</li><li>Each member's actual contribution and lessons learned.</li></ul><p class="tp-note">Replace this starter description with your project's actual scope before presenting it as a completed system.</p></div></div>
      <div class="tp-modal" id="project-quiz" aria-hidden="true" role="dialog" aria-modal="true" aria-labelledby="quiz-title"><div class="tp-modal-backdrop" data-close-modal></div><div class="tp-modal-panel"><button class="tp-modal-close" data-close-modal aria-label="Close project details">×</button><span class="tp-eyebrow">ACADEMIC PROJECT / INTERACTIVE APPLICATION</span><h2 id="quiz-title">Quiz Game</h2><p>This project is presented as a starter portfolio entry for an interactive quiz game. Add the topic or subject, intended players, and how a player completes a quiz round.</p><h3>Project information to complete</h3><ul><li>Game objective and target users.</li><li>Confirmed question types, scoring, levels, and feedback features.</li><li>Tools and technologies used.</li><li>Each member's actual contribution and lessons learned.</li></ul><p class="tp-note">Replace this starter description with your project's actual scope before presenting it as a completed application.</p></div></div>
      <?php echo tp_footer(); ?>
    </div>
    <?php return ob_get_clean();
}

/** Create the member pages automatically when the theme is activated or admin is opened. */
function tp_ensure_member_pages() {
    if ( ! current_user_can( 'edit_pages' ) ) { return; }
    $pages = array(
        'chloe' => array( 'title' => 'Chloe Lou D. Brotonel', 'content' => '[team_member_portfolio member="chloe"]' ),
        'john-bryan' => array( 'title' => 'John Bryan T. Mandario', 'content' => '[team_member_portfolio member="john-bryan"]' ),
        'keziyah' => array( 'title' => 'Keziyah Jarelle C. Lopez', 'content' => '[team_member_portfolio member="keziyah"]' ),
    );
    foreach ( $pages as $slug => $page ) {
        $existing = get_page_by_path( $slug, OBJECT, 'page' );
        if ( $existing ) {
            if ( false === strpos( $existing->post_content, '[team_member_portfolio' ) ) {
                wp_update_post( array( 'ID' => $existing->ID, 'post_content' => $page['content'] ) );
            }
            continue;
        }
        wp_insert_post( array(
            'post_type' => 'page', 'post_status' => 'publish',
            'post_title' => $page['title'], 'post_name' => $slug,
            'post_content' => $page['content'],
        ) );
    }
    update_option( 'tp_member_pages_created', '1' );
}
add_action( 'admin_init', 'tp_ensure_member_pages' );
add_action( 'after_switch_theme', function () {
    tp_ensure_member_pages();
    flush_rewrite_rules();
} );

add_shortcode( 'team_group_portfolio', 'tp_group_portfolio_shortcode' );

function tp_member_data() {
    return array(
      'chloe' => array('name'=>'Chloe Lou D. Brotonel','initials'=>'CB','role'=>'IT STUDENT','tagline'=>'Curiosity is where every good idea begins.','intro'=>'I am an Information Technology student at Opol Community College. I am building my knowledge through classes, teamwork, and practical activities. This portfolio brings together my education, skills in progress, projects, and experiences.','about'=>'I am continuing to discover the areas of Information Technology that interest me most. I value learning, patience, and working with others. As I gain more experience, I want to become more confident in applying what I learn to practical projects. I also want to keep improving my communication, problem-solving, and technical skills. Personal interests, hobbies, goals, and career plans can be added here in my own words.','education'=>array(array('school'=>'Your Elementary School Name','course'=>'Elementary Education','years'=>'Add your attendance years','detail'=>'SAMPLE ENTRY — Replace this with your actual elementary school, dates, and any verified recognition.'),array('school'=>'Your Junior/Senior High School Name','course'=>'Junior High School / Senior High School','years'=>'Add your attendance years','detail'=>'SAMPLE ENTRY — Replace this with your actual school, strand (if applicable), dates, and achievements.'),array('school'=>'Opol Community College (edit if needed)','course'=>'Bachelor of Science in Information Technology','years'=>'2023 – Present (edit if needed)','detail'=>'SAMPLE ENTRY — Update your year level, attendance dates, awards, coursework, and school activities.')),'skills'=>array('Programming Languages'=>'Add confirmed languages','Frontend Development'=>'Add confirmed tools and skills','Backend Development'=>'Add confirmed tools and skills','Database Management'=>'Add confirmed database experience','Networking'=>'Add confirmed networking experience','Tools & Software'=>'Add tools used','Soft Skills'=>'Add verified soft skills'),'projects'=>array(array('title'=>'Hotel Management System','category'=>'School Projects','description'=>'A hotel management project. Add the actual purpose, users, and functions included in your version.','details'=>'Project objective: add details. Problem addressed: add details. Features: list only implemented features. Personal contribution: add your role. Development process and lessons learned: add your experience.','tech'=>'Add confirmed technologies'),array('title'=>'Quiz Game','category'=>'School Projects','description'=>'An interactive quiz game project. Add its subject, intended players, and actual gameplay.','details'=>'Project objective: add details. Features: add implemented question types, scoring, and feedback. Personal contribution: add your role. Development process and lessons learned: add your experience.','tech'=>'Add confirmed technologies')),'experience'=>array(array('title'=>'Academic and collaborative activities','organization'=>'Opol Community College','years'=>'Add dates','detail'=>'Add school activities, team responsibilities, volunteer work, immersion, or work experience you have actually completed.')),'achievements'=>array('Add verified academic awards','Add certificates or training','Add IT-related participation or recognition'),'hobbies'=>'Add your hobbies, favorite activities, creative interests, and things you enjoy outside school.','email'=>'Add email address','facebook'=>'','github'=>'','linkedin'=>'','resume'=>''),
      'john-bryan' => array('name'=>'John Bryan T. Mandario','initials'=>'JM','role'=>'IT STUDENT','tagline'=>'Learn by doing. Grow by creating.','intro'=>'I am an Information Technology student at Opol Community College. I continue to develop my knowledge through academic activities, teamwork, and hands-on project work. This portfolio is a place to share what I have learned and the work I have taken part in.','about'=>'I believe learning happens through practice, curiosity, and being open to new challenges. I want to continue building my confidence in Information Technology, improve the way I solve problems, and gain experience by working on useful projects. I value collaboration and learning from the people around me. Add personal interests, hobbies, strengths, and career goals here in your own words.','education'=>array(array('school'=>'Your Elementary School Name','course'=>'Elementary Education','years'=>'Add your attendance years','detail'=>'SAMPLE ENTRY — Replace this with your actual elementary school, dates, and any verified recognition.'),array('school'=>'Your Junior/Senior High School Name','course'=>'Junior High School / Senior High School','years'=>'Add your attendance years','detail'=>'SAMPLE ENTRY — Replace this with your actual school, strand (if applicable), dates, and achievements.'),array('school'=>'Opol Community College (edit if needed)','course'=>'Bachelor of Science in Information Technology','years'=>'2023 – Present (edit if needed)','detail'=>'SAMPLE ENTRY — Update your year level, attendance dates, awards, coursework, and school activities.')),'skills'=>array('Programming Languages'=>'Add confirmed languages','Frontend Development'=>'Add confirmed tools and skills','Backend Development'=>'Add confirmed tools and skills','Database Management'=>'Add confirmed database experience','Networking'=>'Add confirmed networking experience','Tools & Software'=>'Add tools used','Soft Skills'=>'Add verified soft skills'),'projects'=>array(array('title'=>'Hotel Management System','category'=>'School Projects','description'=>'A hotel management project. Add the actual purpose, users, and functions included in your version.','details'=>'Project objective: add details. Problem addressed: add details. Features: list only implemented features. Personal contribution: add your role. Development process and lessons learned: add your experience.','tech'=>'Add confirmed technologies'),array('title'=>'Quiz Game','category'=>'School Projects','description'=>'An interactive quiz game project. Add its subject, intended players, and actual gameplay.','details'=>'Project objective: add details. Features: add implemented question types, scoring, and feedback. Personal contribution: add your role. Development process and lessons learned: add your experience.','tech'=>'Add confirmed technologies')),'experience'=>array(array('title'=>'Academic and collaborative activities','organization'=>'Opol Community College','years'=>'Add dates','detail'=>'Add school activities, team responsibilities, volunteer work, immersion, or work experience you have actually completed.')),'achievements'=>array('Add verified academic awards','Add certificates or training','Add IT-related participation or recognition'),'hobbies'=>'Add your hobbies, favorite activities, creative interests, and things you enjoy outside school.','email'=>'Add email address','facebook'=>'','github'=>'','linkedin'=>'','resume'=>''),
      'keziyah' => array('name'=>'Keziyah Jarelle C. Lopez','initials'=>'KL','role'=>'4TH YEAR BSIT STUDENT','tagline'=>'Code with purpose. Grow with passion.','intro'=>'I am a fourth-year Bachelor of Science in Information Technology student at Opol Community College. I enjoy creative IT work, learning new things, and figuring out how systems connect. I want to keep growing in technology while creating work that has purpose.','about'=>'I would describe myself as someone who is still learning, exploring, and growing. I enjoy working with others, managing my time, and adapting to new situations. Outside technology, I enjoy watching horror, romance, and animated movies, playing badminton, visiting beaches and mountains, gaming, and traveling. I am interested in networking because I enjoy figuring out how connections work, while also exploring creative areas of IT.','education'=>array(array('school'=>'Opol Community College','course'=>'Bachelor of Science in Information Technology','years'=>'2023 – Present','detail'=>'Dean’s Lister (1st Year and 2nd Year); Class Mayor (1st Year and 2nd Year).'),array('school'=>'Opol National Secondary Technical School','course'=>'Senior High School · ABM','years'=>'2020 – 2023','detail'=>'With Honor. Completed work immersion involving DILG-LGMED filing, data encoding, and document-related tasks.'),array('school'=>'Opol National Secondary Technical School','course'=>'Junior High School','years'=>'2016 – 2020','detail'=>'With Honor (Grades 8–10).'),array('school'=>'Opol Central Elementary School','course'=>'Elementary','years'=>'2011 – 2016','detail'=>'With Honor.')),'skills'=>array('Coursework'=>'Computer Systems · Information Management · Digital Technologies','Networking'=>'Interested in networking and understanding how devices and systems connect.','Web Development'=>'Add confirmed languages, tools, and project experience.','Database Management'=>'Add confirmed database tools and experience.','Tools & Software'=>'Add confirmed tools and software.','Soft Skills'=>'Teamwork · Time management · Adaptability'),'projects'=>array(array('title'=>'OPCR & IPCR Monitoring System','category'=>'Web Development','description'=>'A web-based performance monitoring and reporting system for Opol Community College.','details'=>'Add your confirmed individual contribution, objectives, challenges, features handled, and lessons learned.','tech'=>'Laravel · MySQL · Vite'),array('title'=>'Hotel Management System','category'=>'School Projects','description'=>'A hotel management project. Add the actual purpose, users, and functions included in your version.','details'=>'Project objective: add details. Problem addressed: add details. Features: list only implemented features. Personal contribution: add your role. Development process and lessons learned: add your experience.','tech'=>'Add confirmed technologies'),array('title'=>'Quiz Game','category'=>'School Projects','description'=>'An interactive quiz game project. Add its subject, intended players, and actual gameplay.','details'=>'Project objective: add details. Features: add implemented question types, scoring, and feedback. Personal contribution: add your role. Development process and lessons learned: add your experience.','tech'=>'Add confirmed technologies')),'experience'=>array(array('title'=>'Class Mayor','organization'=>'Opol Community College','years'=>'1st Year and 2nd Year','detail'=>'Served as class mayor during the first and second years of BSIT.')),'achievements'=>array('Dean’s Lister — 1st Year','Dean’s Lister — 2nd Year','Class Mayor — 1st Year','Class Mayor — 2nd Year','With Honor — Senior High School','With Honor — Junior High School','With Honor — Elementary'),'hobbies'=>'Watching horror, romance, and animated movies · Badminton · Beaches and mountains · Gaming · Traveling','email'=>'Add email address','facebook'=>'','github'=>'','linkedin'=>'','resume'=>'')
    );
}

function tp_member_portfolio_shortcode( $atts ) {
    $atts = shortcode_atts(array('member'=>'keziyah'),$atts,'team_member_portfolio');
    $all = tp_member_data(); $key = sanitize_key($atts['member']);
    if ( ! isset($all[$key]) ) { return '<p>Portfolio not found.</p>'; }
    $m = $all[$key]; $slug = $key;
    $sections = array('Home'=>'top','About Me'=>'about','Education'=>'education','Skills'=>'skills','Projects'=>'projects','Experience'=>'experience','Achievements'=>'achievements','Contact'=>'contact');
    ob_start(); ?>
    <div class="tp-site tp-person tp-person-<?php echo esc_attr($key); ?>" id="top">
      <?php echo tp_header('Home',true); ?>
      <main>
        <section class="tp-hero tp-person-hero"><div class="tp-hero-copy"><a class="tp-back-link" href="<?php echo esc_url(home_url('/')); ?>">← BACK TO GROUP PORTFOLIO</a><span class="tp-kicker"><i></i> <?php echo tp_e($m['role']); ?> · PERSONAL PORTFOLIO</span><h1><?php echo tp_e($m['name']); ?><br><em><?php echo tp_e($m['tagline']); ?></em></h1><p class="tp-lead"><?php echo tp_e($m['intro']); ?></p><div class="tp-actions"><a class="tp-button tp-button-primary" href="#projects">View my projects ↘</a><a class="tp-button tp-button-ghost" href="#about">About me</a><a class="tp-button tp-button-ghost" href="#contact">Contact me</a></div></div><div class="tp-person-art"><?php echo tp_placeholder_avatar($m['initials'],'tp-person-avatar'); ?><span class="tp-person-art-label">PORTFOLIO<br>PROFILE / 2026</span></div></section>
        <section class="tp-section" id="about"><?php echo tp_section_head('01 / ABOUT ME','A little about who I am.'); ?><div class="tp-about-grid"><div class="tp-about-statement"><span class="tp-quote-mark">“</span><h3><?php echo tp_e($m['tagline']); ?></h3></div><div class="tp-about-details"><p><?php echo tp_e($m['about']); ?></p><p class="tp-note">Personal goals and career aspirations: add your own words here.</p></div></div></section>
        <section class="tp-section tp-muted-section" id="education"><?php echo tp_section_head('02 / EDUCATION','Learning is a journey.'); ?><div class="tp-education-list">
        <?php foreach($m['education'] as $edu): ?><article class="tp-education-card"><span class="tp-timeline-dot"></span><small><?php echo tp_e($edu['years']); ?></small><h3><?php echo tp_e($edu['school']); ?></h3><b><?php echo tp_e($edu['course']); ?></b><p><?php echo tp_e($edu['detail']); ?></p></article><?php endforeach; ?>
        </div></section>
        <section class="tp-section" id="skills"><?php echo tp_section_head('03 / SKILLS','Things I am learning and using.','No percentage ratings are shown. Add or edit each skill to reflect your actual experience.'); ?><div class="tp-skill-grid tp-person-skills">
        <?php foreach($m['skills'] as $title=>$desc): ?><article class="tp-skill-card"><span class="tp-skill-icon">✳</span><h3><?php echo tp_e($title); ?></h3><p><?php echo tp_e($desc); ?></p></article><?php endforeach; ?>
        </div></section>
        <section class="tp-section tp-muted-section" id="projects"><?php echo tp_section_head('04 / SELECTED WORK','Projects and practice.','Explore the work I have contributed to. Add more projects as they are ready to share.'); ?><div class="tp-filter-row" role="group" aria-label="Filter projects"><button class="is-active" data-filter="all">All</button><button data-filter="web-development">Web Development</button><button data-filter="school-projects">School Projects</button><button data-filter="other">Other</button></div><div class="tp-project-grid tp-person-projects">
        <?php if(!empty($m['projects'])): foreach($m['projects'] as $i=>$project): ?><article class="tp-project-card" data-category="<?php echo esc_attr(sanitize_title($project['category'])); ?>"><div class="tp-project-cover cover-opcr"><span>PROJECT / 0<?php echo (int)($i+1); ?></span><div class="tp-screen-ui"><b><?php echo tp_e($project['title']); ?></b></div></div><div class="tp-project-body"><div class="tp-project-meta"><span><?php echo tp_e($project['category']); ?></span></div><h3><?php echo tp_e($project['title']); ?></h3><p><?php echo tp_e($project['description']); ?></p><div class="tp-tags"><span><?php echo tp_e($project['tech']); ?></span></div><button class="tp-text-link tp-inline-detail" data-detail="<?php echo esc_attr($project['details']); ?>">VIEW DETAILS ↗</button></div></article><?php endforeach; else: ?><div class="tp-empty-project"><span>＋</span><h3>Your next project starts here.</h3><p>Add a project title, screenshot, description, technologies, and your personal contribution.</p></div><?php endif; ?>
        </div></section>
        <section class="tp-section" id="experience"><?php echo tp_section_head('05 / EXPERIENCE','Where I have been involved.'); ?><div class="tp-timeline">
        <?php if(!empty($m['experience'])): foreach($m['experience'] as $exp): ?><article class="tp-timeline-item"><span class="tp-timeline-dot"></span><small><?php echo tp_e($exp['years']); ?></small><h3><?php echo tp_e($exp['title']); ?></h3><b><?php echo tp_e($exp['organization']); ?></b><p><?php echo tp_e($exp['detail']); ?></p></article><?php endforeach; else: ?><article class="tp-timeline-item"><span class="tp-timeline-dot"></span><small>ADD EXPERIENCE</small><h3>Internship, immersion, work, or volunteer experience</h3><p>Add only experiences you have actually completed, with dates and your responsibilities.</p></article><?php endif; ?>
        </div></section>
        <section class="tp-section tp-muted-section" id="achievements"><?php echo tp_section_head('06 / ACHIEVEMENTS','Milestones worth remembering.'); ?><div class="tp-achievement-grid">
        <?php if(!empty($m['achievements'])): foreach($m['achievements'] as $achievement): ?><article class="tp-achievement-card"><span>✦</span><p><?php echo tp_e($achievement); ?></p></article><?php endforeach; else: ?><article class="tp-achievement-card"><span>＋</span><p>Add verified awards, certificates, trainings, seminars, or recognitions.</p></article><?php endif; ?>
        </div></section>
        <section class="tp-section" id="interests"><?php echo tp_section_head('07 / BEYOND THE SCREEN','Interests and hobbies.'); ?><div class="tp-interest-panel"><span>✳</span><p><?php echo tp_e($m['hobbies']); ?></p></div></section>
        <section class="tp-section tp-contact" id="contact"><div class="tp-contact-panel"><div><span class="tp-eyebrow">08 / GET IN TOUCH</span><h2>Let's start<br>a conversation.</h2><p>Have a question or want to work together? Feel free to reach out.</p><?php if($m['email']!=='Add email address'): ?><a class="tp-button tp-button-primary" href="mailto:<?php echo esc_attr($m['email']); ?>">Email me ↗</a><?php else: ?><span class="tp-note">Add your email address to activate direct contact.</span><?php endif; ?>
        <div class="tp-socials"><?php foreach(array('facebook'=>'Facebook','github'=>'GitHub','linkedin'=>'LinkedIn') as $field=>$label): if(!empty($m[$field])): ?><a href="<?php echo esc_url($m[$field]); ?>" target="_blank" rel="noopener"><?php echo esc_html($label); ?> ↗</a><?php endif; endforeach; ?></div>
        <?php if(!empty($m['resume'])): ?><a class="tp-button tp-button-ghost" href="<?php echo esc_url($m['resume']); ?>" download>Download resume ↓</a><?php else: ?><p class="tp-note">Resume download will appear after your PDF is added to the site.</p><?php endif; ?>
        </div><form class="tp-contact-form" data-contact-form data-recipient="<?php echo esc_attr($m['email']==='Add email address'?'':$m['email']); ?>"><label>Your name<input name="name" required placeholder="Your name"></label><label>Email address<input name="email" type="email" required placeholder="you@example.com"></label><label>Subject<input name="subject" required placeholder="Subject"></label><label>Message<textarea name="message" rows="4" required placeholder="Write your message..."></textarea></label><button class="tp-button tp-button-primary" type="submit">Prepare message ↗</button><p class="tp-form-note" aria-live="polite">The form prepares an email in your default email app.</p></form></div></section>
      </main>
      <div class="tp-modal" id="detail-modal" aria-hidden="true" role="dialog" aria-modal="true"><div class="tp-modal-backdrop" data-close-modal></div><div class="tp-modal-panel"><button class="tp-modal-close" data-close-modal aria-label="Close details">×</button><span class="tp-eyebrow">PROJECT DETAILS</span><h2>Project information</h2><p class="tp-modal-dynamic"></p></div></div>
      <?php echo tp_footer(true); ?>
    </div>
    <?php return ob_get_clean();
}
add_shortcode( 'team_member_portfolio', 'tp_member_portfolio_shortcode' );
