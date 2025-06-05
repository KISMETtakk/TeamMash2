<?php
$pageTitle = "Endorse Me - Phurutsi Campaign";
$pageDescription = "Endorse Mashitishi B. Phurutsi for a smarter, stronger council. Share your support message.";

require_once 'config/database.php';

$success_message = '';
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $db = (new Database())->getConnection();

    $name = sanitizeInput($_POST['name'] ?? '');
    $email = sanitizeInput($_POST['email'] ?? '');
    $message = sanitizeInput($_POST['message'] ?? '');
    $ip_address = getClientIP();
    $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? '';
    $avatar_path = null;

    // Handle file upload
    if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        $filename = uniqid() . '_' . basename($_FILES['avatar']['name']);
        $uploadPath = $uploadDir . $filename;

        if (move_uploaded_file($_FILES['avatar']['tmp_name'], $uploadPath)) {
            $avatar_path = $uploadPath;
        }
    }

    try {
        $stmt = $db->prepare("INSERT INTO endorsements (name, email, message, avatar_path, ip_address, user_agent) VALUES (:name, :email, :message, :avatar_path, :ip_address, :user_agent)");
        $stmt->execute([
            ':name' => $name,
            ':email' => $email,
            ':message' => $message,
            ':avatar_path' => $avatar_path,
            ':ip_address' => $ip_address,
            ':user_agent' => $user_agent
        ]);
        header("Location: index.php");
        exit();
    } catch (PDOException $e) {
        echo "Error saving endorsement: " . $e->getMessage();
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Verify CSRF token (simple implementation)
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            throw new Exception('Invalid form submission');
        }
        
        // Validate required fields
        if (empty($_POST['name']) || empty($_POST['message']) || !isset($_POST['endorse_checkbox'])) {
            throw new Exception('Please fill in all required fields and confirm your endorsement');
        }
        
        // Simple bot prevention
        if (!empty($_POST['honeypot'])) {
            throw new Exception('Bot detected');
        }
        
        $name = sanitizeInput($_POST['name']);
        $email = sanitizeInput($_POST['email'] ?? '');
        $message = sanitizeInput($_POST['message']);
        $ip_address = getClientIP();
        $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? '';
        
        // Validate name length
        if (strlen($name) < 2 || strlen($name) > 100) {
            throw new Exception('Name must be between 2 and 100 characters');
        }
        
        // Validate message length
        if (strlen($message) < 10 || strlen($message) > 1000) {
            throw new Exception('Message must be between 10 and 1000 characters');
        }
        
        // Validate email if provided
        if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception('Please enter a valid email address');
        }
        
        $database = new Database();
        $db = $database->getConnection();
        
        // Check rate limiting (max 3 endorsements per IP per day)
        $rate_limit_query = "SELECT COUNT(*) as count FROM endorsements 
                            WHERE ip_address = ? AND DATE(created_at) = CURDATE()";
        $rate_stmt = $db->prepare($rate_limit_query);
        $rate_stmt->execute([$ip_address]);
        $daily_count = $rate_stmt->fetch()['count'];
        
        if ($daily_count >= 3) {
            throw new Exception('Maximum endorsements per day reached. Please try again tomorrow.');
        }
        
        // Handle avatar upload
        $avatar_path = null;
        if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = 'uploads/avatars/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }
            
            $file_info = pathinfo($_FILES['avatar']['name']);
            $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
            
            if (in_array(strtolower($file_info['extension']), $allowed_types)) {
                $filename = uniqid() . '.' . $file_info['extension'];
                $target_path = $upload_dir . $filename;
                
                if (move_uploaded_file($_FILES['avatar']['tmp_name'], $target_path)) {
                    $avatar_path = $target_path;
                }
            }
        }
        
        // Insert endorsement
        $query = "INSERT INTO endorsements (name, email, message, avatar_path, ip_address, user_agent) 
                  VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $db->prepare($query);
        $stmt->execute([$name, $email, $message, $avatar_path, $ip_address, $user_agent]);
        
        $success_message = "Thank you for your endorsement! Your support means a lot.";
        
        // Clear form data
        $_POST = [];
        
    } catch (Exception $e) {
        $error_message = $e->getMessage();
        error_log("Endorsement submission error: " . $e->getMessage());
    }
}

// Generate CSRF token
session_start();
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Fetch all approved endorsements
try {
    $database = new Database();
    $db = $database->getConnection();
    
    $query = "SELECT name, message, avatar_path, created_at FROM endorsements 
              WHERE is_approved = 1 ORDER BY created_at DESC";
    $stmt = $db->prepare($query);
    $stmt->execute();
    $endorsements = $stmt->fetchAll();
    
} catch (Exception $e) {
    error_log("Error fetching endorsements: " . $e->getMessage());
    $endorsements = [];
}

include 'includes/header.php';
?>

<!-- Endorsement Form Section -->
<section class="endorsement-form-section">
    <div class="container">
        <div class="section-header">
            <h1 class="section-title animate-fade-in">Endorse Mashitishi B. Phurutsi</h1>
            <p class="section-subtitle animate-fade-in-delay">
                Your voice matters. Share why you support my vision for a smarter, stronger council.
            </p>
        </div>
        
        <div class="form-container animate-fade-in-delay-2">
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
            
            <form action="endorse.php" id="endorsement-form" method="POST" enctype="multipart/form-data" class="endorsement-form">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                <input type="text" name="honeypot" style="display: none;">
                
                <!-- POPIA Disclaimer -->
                <div class="popia-disclaimer">
                    <h3><i class="fas fa-shield-alt"></i> Privacy Notice (POPIA)</h3>
                    <p>
                        By submitting this form, you consent to the collection and processing of your personal 
                        information for the purpose of displaying your endorsement. Your information will be 
                        kept secure and used only for campaign purposes.
                    </p>
                    <p>
                        <a href="#" onclick="showPopiaModal()">Read full privacy policy</a>
                    </p>
                </div>
                
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
                            <i class="fas fa-envelope"></i> Email (Optional)
                        </label>
                        <input 
                            type="email" 
                            id="email" 
                            name="email"
                            value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"
                            placeholder="your.email@example.com"
                        >
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="message">
                        <i class="fas fa-comment"></i> Your Endorsement Message *
                    </label>
                    <textarea 
                        id="message" 
                        name="message" 
                        required 
                        rows="5" 
                        maxlength="1000"
                        placeholder="Share why you endorse Mashitishi B. Phurutsi and his vision for higher education governance..."
                    ><?php echo htmlspecialchars($_POST['message'] ?? ''); ?></textarea>
                    <div class="character-count">
                        <span id="char-count">0</span>/1000 characters
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="avatar">
                        <i class="fas fa-camera"></i> Profile Picture (Optional)
                    </label>
                    <div class="file-upload">
                        <input type="file" id="avatar" name="avatar" accept="image/*">
                        <div class="file-upload-display">
                            <i class="fas fa-cloud-upload-alt"></i>
                            <span>Click to upload or drag and drop</span>
                            <small>JPG, PNG, GIF up to 5MB</small>
                        </div>
                    </div>
                </div>
                
                <div class="form-group checkbox-group">
                    <label class="checkbox-label">
                        <input type="checkbox" name="endorse_checkbox" required>
                        
                        <span class="checkbox-text">
                            <strong>I endorse Mashitishi B. Phurutsi</strong> for a position that will contribute 
                            to a smarter, stronger council in higher education governance.
                        </span>
                    </label>
                </div>
                
                <!-- Simple Captcha -->
                <div class="form-group captcha-group">
                    <label for="captcha">
                        <i class="fas fa-shield-alt"></i> Security Check: What is 5 + 3? *
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
                        <i class="fas fa-heart"></i> Submit Endorsement
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>

<!-- Endorsements Display -->
 <!-- <?php if (!empty($endorsements)): ?>
<section class="endorsements-display">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Community Endorsements</h2>
            <p class="section-subtitle">
                <?php echo count($endorsements); ?> people have endorsed this campaign
            </p>
        </div>
        
        <div class="endorsements-grid" id="endorsements-grid">
            <?php foreach ($endorsements as $index => $endorsement): ?>
            <div class="endorsement-card animate-on-scroll" style="animation-delay: <?php echo ($index % 6) * 0.1; ?>s">
                <div class="endorsement-header">
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
                    <div class="endorsement-info">
                        <h4 class="endorsement-name">
                            <?php echo htmlspecialchars($endorsement['name']); ?>
                        </h4>
                        <span class="endorsement-date">
                            <?php echo date('M j, Y', strtotime($endorsement['created_at'])); ?>
                        </span>
                    </div>
                </div>
                <div class="endorsement-content">
                    <p class="endorsement-message">
                        "<?php echo htmlspecialchars($endorsement['message']); ?>"
                    </p>
                </div>
                <div class="endorsement-footer">
                    <i class="fas fa-heart text-red"></i>
                    <span>Endorsed</span>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>  -->

<!-- Call to Action -->
<section class="cta">
    <div class="container">
        <div class="cta-content animate-on-scroll">
            <h2>Join the Movement</h2>
            <p>Be part of the change for smarter, stronger higher education governance</p>
            <div class="cta-stats">
                <div class="stat">
                    <span class="stat-number"><?php echo count($endorsements); ?></span>
                    <span class="stat-label">Endorsements</span>
                </div>
                <div class="stat">
                    <span class="stat-number">1000+</span>
                    <span class="stat-label">Students Impacted</span>
                </div>
                <div class="stat">
                    <span class="stat-number">13</span>
                    <span class="stat-label">Years Experience</span>
                </div>
            </div>
            <a href="#endorsement-form" class="btn btn-primary btn-lg scroll-to">
                <i class="fas fa-arrow-up"></i> Endorse Now
            </a>
        </div>
    </div>
</section>

<script>
// Character counter for message textarea
document.getElementById('message').addEventListener('input', function() {
    const charCount = this.value.length;
    document.getElementById('char-count').textContent = charCount;
    
    if (charCount > 900) {
        document.getElementById('char-count').style.color = '#e74c3c';
    } else {
        document.getElementById('char-count').style.color = '#666';
    }
});

// File upload preview
document.getElementById('avatar').addEventListener('change', function() {
    const file = this.files[0];
    const display = document.querySelector('.file-upload-display');
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            display.innerHTML = `
                <img src="${e.target.result}" alt="Preview" style="max-width: 100px; max-height: 100px; border-radius: 50%;">
                <span>File selected: ${file.name}</span>
            `;
        };
        reader.readAsDataURL(file);
    }
});

// Form validation
document.getElementById('endorsement-form').addEventListener('submit', function(e) {
    const captcha = document.getElementById('captcha').value;
    if (parseInt(captcha) !== 8) {
        e.preventDefault();
        alert('Please answer the security question correctly.');
        return false;
    }
});
</script>

<?php include 'includes/footer.php'; ?>