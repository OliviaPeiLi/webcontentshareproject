<?php if (!class_exists('CFRuntime')) die('No direct access allowed.');
/**
 * Stores your AWS account information. Add your account information, and then rename this file
 * to 'config.inc.php'.
 *
 * @version 2011.06.02
 * @license See the included NOTICE.md file for more information.
 * @copyright See the included NOTICE.md file for more information.
 * @link http://aws.amazon.com/php/ PHP Developer Center
 * @link http://aws.amazon.com/security-credentials AWS Security Credentials
 */


/**
 *Define the sender's email
 */
define('AWS_SES_FROM_EMAIL', '"Fandrop" <team@fandrop.com>');


/**
 * Amazon Web Services Key. Found in the AWS Security Credentials. You can also pass this value as the first
 * parameter to a service constructor.
 */
define('AWS_KEY', 'AKIAIEUK7KIQPXEWI57A');

/**
 * Amazon Web Services Secret Key. Found in the AWS Security Credentials. You can also pass this value as
 * the second parameter to a service constructor.
 */
define('AWS_SECRET_KEY', 'JR716CESdaVvua6SnssXIilaTb/NyMU/5cIoTh2i');

/**
 * Amazon Account ID without dashes. Used for identification with Amazon EC2. Found in the AWS Security
 * Credentials.
 */
define('AWS_ACCOUNT_ID', '');

/**
 * Your CanonicalUser ID. Used for setting access control settings in AmazonS3. Found in the AWS Security
 * Credentials.
 */
define('AWS_CANONICAL_ID', '');

/**
 * Your CanonicalUser DisplayName. Used for setting access control settings in AmazonS3. Found in the AWS
 * Security Credentials (i.e. "Welcome, AWS_CANONICAL_NAME").
 */
define('AWS_CANONICAL_NAME', '');

/**
 * Determines which Cerificate Authority file to use.
 *
 * A value of boolean `false` will use the Certificate Authority file available on the system. A value of
 * boolean `true` will use the Certificate Authority provided by the SDK. Passing a file system path to a
 * Certificate Authority file (chmodded to `0755`) will use that.
 *
 * Leave this set to `false` if you're not sure.
 */
define('AWS_CERTIFICATE_AUTHORITY', false);

/**
 * 12-digit serial number taken from the Gemalto device used for Multi-Factor Authentication. Ignore this
 * if you're not using MFA.
 */
define('AWS_MFA_SERIAL', '');

/**
 * Amazon CloudFront key-pair to use for signing private URLs. Found in the AWS Security Credentials. This
 * can be set programmatically with <AmazonCloudFront::set_keypair_id()>.
 */
define('AWS_CLOUDFRONT_KEYPAIR_ID', '');

/**
 * The contents of the *.pem private key that matches with the CloudFront key-pair ID. Found in the AWS
 * Security Credentials. This can be set programmatically with <AmazonCloudFront::set_private_key()>.
 */
define('AWS_CLOUDFRONT_PRIVATE_KEY_PEM', '');

/**
 * Set the value to true to enable autoloading for classes not prefixed with "Amazon" or "CF". If enabled,
 * load `sdk.class.php` last to avoid clobbering any other autoloaders.
 */
define('AWS_ENABLE_EXTENSIONS', 'false');
