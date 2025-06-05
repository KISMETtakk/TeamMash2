<?php
$pageTitle = "Contact Mashitishi B. Phurutsi - Get in Touch";
$pageDescription = "Contact Mashitishi B. Phurutsi for campaign inquiries, collaboration opportunities, or to learn more about his vision for higher education governance.";

// Start session at the beginning
session_start();

// Generate CSRF token if not exists
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$success_message = '';
$error_message = '';

// Handle contact form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Verify CSRF token
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            throw new Exception('Invalid form submission');
        }
        
        // Simple captcha check
        if (!isset($_POST['captcha']) || $_POST['captcha'] != '11') {
            throw new Exception('Please answer the security question correctly (7 + 4 = 11)');
        }
        
        // Validate required fields
        if (empty($_POST['name']) || empty($_POST['email']) || empty($_POST['message'])) {
            throw new Exception('Please fill in all required fields');
        }
        
        // Simple bot prevention
        if (!empty($_POST['honeypot'])) {
            throw new Exception('Bot detected');
        }
        
        $name = sanitizeInput($_POST['name']);
        $email = sanitizeInput($_POST['email']);
        $subject = sanitizeInput($_POST['subject'] ?? 'General Inquiry');
        $message = sanitizeInput($_POST['message']);
        $ip_address = getClientIP();
        
        // Validate inputs
        if (strlen($name) < 2 || strlen($name) > 100) {
            throw new Exception('Name must be between 2 and 100 characters');
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception('Please enter a valid email address');
        }
        
        if (strlen($message) < 10 || strlen($message) > 2000) {
            throw new Exception('Message must be between 10 and 2000 characters');
        }
        
        // Try to connect to database with timeout
        require_once 'config/database.php';
        $database = new Database();
        $db = $database->getConnection();
        
        if (!$db) {
            throw new Exception('Database connection failed. Please try again later.');
        }
        
        // Check rate limiting (max 5 messages per IP per day)
        $rate_limit_query = "SELECT COUNT(*) as count FROM contact_messages 
                            WHERE ip_address = ? AND DATE(created_at) = CURDATE()";
        $rate_stmt = $db->prepare($rate_limit_query);
        $rate_stmt->execute([$ip_address]);
        $daily_count = $rate_stmt->fetch()['count'];
        
        if ($daily_count >= 5) {
            throw new Exception('Maximum messages per day reached. Please try again tomorrow.');
        }
        
        // Insert contact message
        $query = "INSERT INTO contact_messages (name, email, subject, message, ip_address) 
                  VALUES (?, ?, ?, ?, ?)";
        $stmt = $db->prepare($query);
        $result = $stmt->execute([$name, $email, $subject, $message, $ip_address]);
        
        if (!$result) {
            throw new Exception('Failed to save message. Please try again.');
        }
        
        $success_message = "Thank you for your message! We'll get back to you soon.";
        
        // Clear form data
        $_POST = [];
        
    } catch (Exception $e) {
        $error_message = $e->getMessage();
        error_log("Contact form error: " . $e->getMessage());
    }
}

// Helper functions
function sanitizeInput($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

function getClientIP() {
    $ipkeys = ['HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'REMOTE_ADDR'];
    foreach ($ipkeys as $key) {
        if (array_key_exists($key, $_SERVER) === true) {
            foreach (explode(',', $_SERVER[$key]) as $ip) {
                $ip = trim($ip);
                if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false) {
                    return $ip;
                }
            }
        }
    }
    return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
}

include 'includes/header.php';
?>

<!-- Contact Hero Section -->
<section class="contact-hero">
    <div class="container">
        <div class="contact-hero-content">
            <h1 class="contact-title animate-fade-in">Get in Touch</h1>
            <p class="contact-subtitle animate-fade-in-delay">
                Have questions about my campaign or vision? Want to collaborate on educational innovation? 
                I'd love to hear from you.
            </p>
        </div>
    </div>
</section>

<!-- Contact Form & Info Section -->
<section class="contact-section">
    <div class="container">
        <div class="contact-content">
            <!-- Contact Information -->
            <div class="contact-info animate-on-scroll">
                <h2>Let's Connect</h2>
                <p>
                    Whether you're interested in supporting my campaign, discussing educational innovation, 
                    or exploring collaboration opportunities, I'm always open to meaningful conversations.
                </p>
                
                <div class="contact-methods">
                    <div class="contact-method">
                        <div class="method-icon">
                            <i class="fab fa-linkedin"></i>
                        </div>
                        <div class="method-info">
                            <h4>LinkedIn</h4>
                            <p>Connect with me professionally</p>
                            <a href="https://www.linkedin.com/in/mashitishi-b-phurutsi-626975a/" 
                               target="_blank" class="method-link">
                                View Profile <i class="fas fa-external-link-alt"></i>
                            </a>
                        </div>
                    </div>
                    
                    <div class="contact-method">
                        <div class="method-icon">
                            <i class="fab fa-orcid"></i>
                        </div>
                        <div class="method-info">
                            <h4>ORCID</h4>
                            <p>Academic research profile</p>
                            <a href="https://orcid.org/my-orcid?orcid=0000-0003-3148-985X" 
                               target="_blank" class="method-link">
                                View Research <i class="fas fa-external-link-alt"></i>
                            </a>
                        </div>
                    </div>
                    
                    <div class="contact-method">
                        <div class="method-icon">
                            <i class="fas fa-university"></i>
                        </div>
                        <div class="method-info">
                            <h4>TUT Faculty of ICT</h4>
                            <p>Academic affiliation</p>
                            <span class="method-text">13 years of service</span>
                        </div>
                    </div>
                    
                    <div class="contact-method">
                        <div class="method-icon">
                            <i class="fas fa-code"></i>
                        </div>
                        <div class="method-info">
                            <h4>ICEP Program</h4>
                            <p>Founder & Director</p>
                            <span class="method-text">1000+ students empowered</span>
                        </div>
                    </div>
                </div>
                
                <div class="social-connect">
                    <h3>Follow the Campaign</h3>
                    <div class="social-links">
                        <a href="https://www.linkedin.com/in/mashitishi-b-phurutsi-626975a/" 
                           target="_blank" class="social-link">
                            <i class="fab fa-linkedin"></i>
                        </a>
                        <a href="https://orcid.org/my-orcid?orcid=0000-0003-3148-985X" 
                           target="_blank" class="social-link">
                            <i class="fab fa-orcid"></i>
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Contact Form -->
            <div class="contact-form-container animate-on-scroll">
                <h2>Send a Message</h2>
                
                <?php if ($success_message): ?>
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i>
                        <?php echo htmlspecialchars($success_message); ?>
                    </div>
                <?php endif; ?>
                
                <?php if ($error_message): ?>
                    <div class="alert alert-error">
                        <i class="fas fa-exclamation-circle"></i>
                        <?php echo htmlspecialchars($error_message); ?>
                    </div>
                <?php endif; ?>
                
                <form id="contact-form" method="POST" class="contact-form">
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                    <input type="text" name="honeypot" style="display: none;">
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="name">
                                <i class="fas fa-user"></i> Full Name *
                            </label>
                            <input 
                                type="text" 
                                id="name" 
                                name="name" 
                                required 
                                maxlength="100"
                                value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>"
                                placeholder="Enter your full name"
                            >
                        </div>
                        
                        <div class="form-group">
                            <label for="email">
                                <i class="fas fa-envelope"></i> Email Address *
                            </label>
                            <input 
                                type="email" 
                                id="email" 
                                name="email" 
                                required
                                value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"
                                placeholder="your.email@example.com"
                            >
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="subject">
                            <i class="fas fa-tag"></i> Subject
                        </label>
                        <select id="subject" name="subject">
                            <option value="General Inquiry">General Inquiry</option>
                            <option value="Campaign Support">Campaign Support</option>
                            <option value="Collaboration">Collaboration Opportunity</option>
                            <option value="Media Inquiry">Media Inquiry</option>
                            <option value="Academic Discussion">Academic Discussion</option>
                            <option value="ICEP Program">ICEP Program</option>
                            <option value="Hackathon">Hackathon Inquiry</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="message">
                            <i class="fas fa-comment"></i> Your Message *
                        </label>
                        <textarea 
                            id="message" 
                            name="message" 
                            required 
                            rows="6" 
                            maxlength="2000"
                            placeholder="Share your thoughts, questions, or collaboration ideas..."
                        ><?php echo htmlspecialchars($_POST['message'] ?? ''); ?></textarea>
                        <div class="character-count">
                            <span id="char-count">0</span>/2000 characters
                        </div>
                    </div>
                    
                    <!-- Simple Captcha -->
                    <div class="form-group captcha-group">
                        <label for="captcha">
                            <i class="fas fa-shield-alt"></i> Security Check: What is 7 + 4? *
                        </label>
                        <input 
                            type="number" 
                            id="captcha" 
                            name="captcha" 
                            required 
                            placeholder="Enter the answer"
                        >
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-paper-plane"></i> Send Message
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- Campaign Info Section -->
<section class="campaign-info">
    <div class="container">
        <div class="campaign-content">
            <div class="campaign-text animate-on-scroll">
                <h2>Join the Movement</h2>
                <p>
                    This campaign is about more than just governance—it's about transforming higher education 
                    to better serve our communities and prepare students for the future.
                </p>
                
                <div class="campaign-points">
                    <div class="point">
                        <i class="fas fa-check-circle"></i>
                        <span>Innovative educational approaches</span>
                    </div>
                    <div class="point">
                        <i class="fas fa-check-circle"></i>
                        <span>Community-focused initiatives</span>
                    </div>
                    <div class="point">
                        <i class="fas fa-check-circle"></i>
                        <span>Technology-driven solutions</span>
                    </div>
                    <div class="point">
                        <i class="fas fa-check-circle"></i>
                        <span>Student empowerment programs</span>
                    </div>
                </div>
                
                <div class="campaign-cta">
                    <a href="endorse.php" class="btn btn-primary btn-lg">
                        <i class="fas fa-heart"></i> Endorse My Campaign
                    </a>
                    <a href="about.php" class="btn btn-outline btn-lg">
                        <i class="fas fa-user"></i> Learn More About Me
                    </a>
                </div>
            </div>
            
            <div class="campaign-stats animate-on-scroll">
                <div class="stat-item">
                    <span class="stat-number" data-target="13">0</span>
                    <span class="stat-label">Years at TUT</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number" data-target="1000">0</span>
                    <span class="stat-label">Students Empowered</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number" data-target="50">0</span>
                    <span class="stat-label">Innovations</span>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>

<script>
// Character counter for message textarea
document.addEventListener('DOMContentLoaded', function() {
    const messageTextarea = document.getElementById('message');
    const charCount = document.getElementById('char-count');
    
    if (messageTextarea && charCount) {
        messageTextarea.addEventListener('input', function() {
            charCount.textContent = this.value.length;
        });
        
        // Initialize count
        charCount.textContent = messageTextarea.value.length;
    }
});
</script>