<?php
$pageTitle = "Home - Endorse Phurutsi";
$pageDescription = "Mashitishi B. Phurutsi - Tech-driven educator and digital innovator. For A Smarter, Stronger Council, Vote Phurutsi";

require_once 'config/database.php';

// Get recent endorsements for display
try {
    $database = new Database();
    $db = $database->getConnection();
    
    $query = "SELECT name, message, avatar_path, created_at FROM endorsements 
              WHERE is_approved = 1 ORDER BY created_at DESC LIMIT 6";
    $stmt = $db->prepare($query);
    $stmt->execute();
    $recent_endorsements = $stmt->fetchAll();
    
    $endorsement_count_query = "SELECT COUNT(*) as total FROM endorsements WHERE is_approved = 1";
    $count_stmt = $db->prepare($endorsement_count_query);
    $count_stmt->execute();
    $endorsement_count = $count_stmt->fetch()['total'];
    
} catch(Exception $e) {
    error_log("Error fetching endorsements: " . $e->getMessage());
    $recent_endorsements = [];
    $endorsement_count = 0;
}

include 'includes/header.php';
?>

<!-- Hero Section -->
<section class="hero">
    <div class="hero-background">
        <div class="hero-overlay"></div>
    </div>
    <div class="hero-container">
        <div class="hero-content">
            <div class="hero-text">
                <h1 class="hero-title animate-fade-in">
                    I'm <span class="highlight">Mashitishi B. Phurutsi</span>
                </h1>
                <h2 class="hero-subtitle animate-fade-in-delay">
                    Proudly known as <span class="highlight">Mr Mash-IT</span>
                </h2>
                <p class="hero-description animate-fade-in-delay-2">
                    A tech-driven educator, digital innovator, and passionate advocate for transforming education 
                    in historically disadvantaged communities. With 13 years at TUT's Faculty of ICT and a mission 
                    to make learning vibrant and fashionable.
                </p>
                <div class="hero-stats animate-fade-in-delay-3">
                    <div class="stat">
                        <span class="stat-number" data-target="13">0</span>
                        <span class="stat-label">Years at TUT</span>
                    </div>
                    <div class="stat">
                        <span class="stat-number" data-target="1000">0</span>
                        <span class="stat-label">Students Empowered</span>
                    </div>
                    <div class="stat">
                        <span class="stat-number" data-target="<?php echo $endorsement_count; ?>">0</span>
                        <span class="stat-label">Endorsements</span>
                    </div>
                </div>
                <div class="hero-cta animate-fade-in-delay-4">
                    <a href="endorse.php" class="btn btn-primary btn-lg">
                        <i class="fas fa-heart"></i> Endorse Me
                    </a>
                    <a href="about.php" class="btn btn-secondary btn-lg">
                        <i class="fas fa-user"></i> Learn More
                    </a>
                </div>
            </div>
            <div class="hero-image animate-slide-in-right">
                <img src="images/photo7.jpg" alt="Mashitishi B. Phurutsi - Professional Photo" class="professional-photo">
            </div>
        </div>
    </div>
    <div class="hero-scroll">
        <a href="#mission" class="scroll-indicator">
            <i class="fas fa-chevron-down"></i>
        </a>
    </div>
</section>

<section class="tshiamo-builds-collage">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">My Mission</h2>
            <p class="section-subtitle">Building bridges between academia, industry, and communities</p>
        </div>
  <div class="tshiamo-builds-timeline">
    <div class="tshiamo-builds-timeline-item" tabindex="0">
      <img src="images/photo1.jpg" alt="Kickoff Event 2023">
      <div class="tshiamo-builds-annotation">
        <h3>Kickoff Event</h3>
        <p>Launched our project with an amazing team in 2023!</p>
      </div>
    </div>
    <div class="tshiamo-builds-timeline-item" tabindex="0">
      <img src="images/photo2.jpeg" alt="Community Outreach">
      <div class="tshiamo-builds-annotation">
        <h3>Community Outreach</h3>
        <p>Engaged with local communities to spread our mission.</p>
      </div>
    </div>
    <div class="tshiamo-builds-timeline-item" tabindex="0">
      <img src="images/photo3.jpg" alt="Innovation Hackathon">
      <div class="tshiamo-builds-annotation">
        <h3>Innovation Hackathon</h3>
        <p>Collaborated with students and industry leaders to innovate solutions.</p>
      </div>
    </div>
    <div class="tshiamo-builds-timeline-item" tabindex="0">
      <img src="images/photo4.jpg" alt="Graduation Day">
      <div class="tshiamo-builds-annotation">
        <h3>Graduation Day</h3>
        <p>Celebrating our achievements with the community.</p>
      </div>
    </div>
        <div class="tshiamo-builds-timeline-item" tabindex="0">
      <img src="images/photo5.jpg" alt="Graduation Day">
      <div class="tshiamo-builds-annotation">
        <h3>Innovation Hackathon</h3>
        <p>Celebrating our achievements with the community.</p>
      </div>
    </div>
        <div class="tshiamo-builds-timeline-item" tabindex="0">
      <img src="images/photo6.jpg" alt="Graduation Day">
      <div class="tshiamo-builds-annotation">
        <h3>Community Outreach</h3>
        <p>Celebrating our achievements with the community.</p>
      </div>
    </div>
    </div>
  </div>
</section>

<!-- Mission Section -->
<section id="mission" class="mission">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">My Mission</h2>
            <p class="section-subtitle">Building bridges between academia, industry, and communities</p>
        </div>
        
        <div class="mission-content">
            <div class="mission-card animate-on-scroll">
                <div class="mission-icon">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <h3>Educational Innovation</h3>
                <p>Transforming higher education through technology and innovative teaching methods that prepare students for the 4IR+ revolution.</p>
            </div>
            
            <div class="mission-card animate-on-scroll">
                <div class="mission-icon">
                    <i class="fas fa-users"></i>
                </div>
                <h3>Community Engagement</h3>
                <p>Empowering historically disadvantaged communities through accessible technology education and digital literacy programs.</p>
            </div>
            
            <div class="mission-card animate-on-scroll">
                <div class="mission-icon">
                    <i class="fas fa-lightbulb"></i>
                </div>
                <h3>Innovation Leadership</h3>
                <p>Leading hackathons and innovation programs that connect students with real-world challenges and industry solutions.</p>
            </div>
        </div>
    </div>
</section>

<!-- Campaign Slogan -->
<section class="campaign-slogan">
    <div class="container">
        <div class="slogan-content animate-on-scroll">
            <h2 class="slogan-text">"For A Smarter, Stronger Council, Vote Phurutsi"</h2>
            <p class="slogan-description">
                This platform is your window into bold ideas, community impact, and a smarter, 
                stronger future for higher education governance.
            </p>
            <a href="endorse.php" class="btn btn-primary btn-lg">
                <i class="fas fa-vote-yea"></i> Support My Campaign
            </a>
        </div>
    </div>
</section>

<!-- Recent Endorsements -->
<?php if (!empty($recent_endorsements)): ?>
<section class="recent-endorsements">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Recent Endorsements</h2>
            <p class="section-subtitle">What supporters are saying</p>
        </div>
        
        <div class="endorsements-grid">
            <?php foreach ($recent_endorsements as $endorsement): ?>
            <div class="endorsement-card animate-on-scroll">
                <div class="endorsement-avatar">
                    <?php if ($endorsement['avatar_path']): ?>
                        <img src="<?php echo htmlspecialchars($endorsement['avatar_path']); ?>" 
                             alt="<?php echo htmlspecialchars($endorsement['name']); ?>">
                    <?php else: ?>
                        <div class="avatar-initials">
                            <?php echo generateAvatar($endorsement['name']); ?>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="endorsement-content">
                    <p class="endorsement-message">
                        "<?php echo htmlspecialchars($endorsement['message']); ?>"
                    </p>
                    <div class="endorsement-author">
                        <strong><?php echo htmlspecialchars($endorsement['name']); ?></strong>
                        <span class="endorsement-date">
                            <?php echo date('M j, Y', strtotime($endorsement['created_at'])); ?>
                        </span>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        
        <div class="text-center">
            <a href="endorse.php" class="btn btn-outline">
                <i class="fas fa-eye"></i> View All Endorsements
            </a>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Call to Action -->
<section class="cta">
    <div class="container">
        <div class="cta-content animate-on-scroll">
            <h2>Ready to Support Change?</h2>
            <p>Join the movement for smarter, stronger higher education governance</p>
            <div class="cta-buttons">
                <a href="endorse.php" class="btn btn-primary btn-lg">
                    <i class="fas fa-heart"></i> Endorse Now
                </a>
                <a href="contact.php" class="btn btn-secondary btn-lg">
                    <i class="fas fa-envelope"></i> Get in Touch
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Team Section -->
<section id="team" class="team">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Meet The Team</h2>
            <p class="section-subtitle">ICEP Innovators, Building Tomorrow</p>
        </div>
        
        <div class="team-content">
            <div class="team-card animate-on-scroll">
                <div class="team-image">
                    <img src="images/Msizi.jpg" alt="Msizi Makaula">
                </div>
                <h3>Msizi Makaula</h3>
                <p>FrontEnd Developer</p>
                <a href="https://www.linkedin.com/in/msizi-makaula-619a742ab/" target="_blank" rel="noopener noreferrer">LinkedIn Profile</a>
            </div>
            
            <div class="team-card animate-on-scroll">
                <div class="team-image">
                    <img src="images/Tshiamo.jpg" alt="Tshiamo Matiza">
                </div>
                <h3>Tshiamo Matiza</h3>
                <p>FullStack Developer</p>
                 <a href="https://www.linkedin.com/in/tshiamo-matiza-3685a42a5" target="_blank" rel="noopener noreferrer">LinkedIn Profile</a>
            </div>
            
            <div class="team-card animate-on-scroll">
                <div class="team-image">
                    <img src="images/Gucci.jpg" alt="Michael Sibanda">
                </div>
                <h3>Michael Sibanda</h3>
                <p>Scrum Master</p>
                <a href="https://www.linkedin.com/in/michael-sibanda-64ba42245?utm_source=share&utm_campaign=share_via&utm_content=profile&utm_medium=ios_app" target="_blank" rel="noopener noreferrer">LinkedIn Profile</a>
                
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>