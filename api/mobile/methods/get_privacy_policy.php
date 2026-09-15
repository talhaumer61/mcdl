<?php
if($data_arr['method_name'] == "get_privacy_policy") {

    $privacy_policy = [
        [
            'title'   => SITE_NAME.' Privacy Policy',
            'intro'   => 'Directorate of Open and Distance Learning is committed to protecting the privacy of our users. This Privacy Policy outlines how we collect, use, disclose, and safeguard your personal information when you visit our website <a href="https://dodl.mul.edu.pk/" class="text-primary">Directorate of Open and Distance Learning</a>  and use our services.',

            'sections' => [
                [
                    'heading' => 'Information We Collect',
                    'paragraph-1'   => 'We may collect personal information directly from you when you register, enroll in courses, participate in forums, complete surveys, or interact with our services. Types of personal information may include:',
                    'points'  => [
                        'Contact Information (name, email, address, phone number)',
                        'Account Credentials (username, password)',
                        'Payment Information (credit card details)',
                        'Academic and Professional Information (education, qualifications, employment history)',
                        'Communication Preferences',
                    ],
                    'paragraph-2'   => 'We may also automatically collect certain information about your device and usage of the Site, such as your IP address, browser type, operating system, referring URLs, and pages viewed. This information helps us analyze trends, administer the Site, track users movements around the Site, and gather demographic information about our user base.'
                ],
                [
                    'heading' => 'How We Use Your Information',
                    'paragraph-1' => 'We use the information we collect for various purposes, including to:',
                    'points'  => [
                        'Provide and personalize our services',
                        'Process enrollments and transactions',
                        'Communicate with you about your account and courses',
                        'Respond to your inquiries and provide customer support',
                        'Analyze and improve the quality of our services',
                        'Send you promotional and marketing communications',
                        'Comply with legal obligations'
                    ],
                    'paragraph-2' => ''
                ],
                [
                    'heading' => 'Information Sharing',
                    'paragraph-1' => 'We may share your personal information with third-party service providers who assist us in providing and managing our services, such as payment processors, hosting providers, and marketing platforms. These service providers are obligated to protect your information and are prohibited from using it for any other purpose.',
                    'points'  => [],
                    'paragraph-2' => 'We may also disclose your information in response to legal requests, such as court orders or subpoenas, or to protect our rights, property, or safety, or the rights, property, or safety of others.'
                ],
                [
                    'heading' => 'Data Security',
                    'paragraph-1' => 'We implement reasonable security measures to protect your personal information from unauthorized access, alteration, disclosure, or destruction. However, no method of transmission over the internet or electronic storage is completely secure, so we cannot guarantee absolute security.',
                    'points'  => [],
                    'paragraph-2' => ''
                ],
                [
                    'heading' => 'Your Choices',
                    'paragraph-1' => 'You may update or correct your account information at any time by logging into your account settings. You may also unsubscribe from our marketing communications by following the instructions provided in the messages.',
                    'points'  => [],
                    'paragraph-2' => ''
                ],
                [
                    'heading' => 'Children\'s Privacy',
                    'paragraph-1' => 'Our services are not directed to individuals under the age of 13, and we do not knowingly collect personal information from children under 13. If you become aware that a child under 13 has provided us with personal information, please contact us immediately.',
                    'points'  => [],
                    'paragraph-2' => ''
                ],
                [
                    'heading' => 'Changes to This Privacy Policy',
                    'paragraph-1' => 'We may update this Privacy Policy from time to time to reflect changes in our practices or applicable laws. We will notify you of any material changes by posting the updated Privacy Policy on the Site. Your continued use of the Site after the effective date of the revised Privacy Policy constitutes your acceptance of the changes.',
                    'points'  => [],
                    'paragraph-2' => ''
                ],
                [
                    'heading' => 'Contact Us',
                    'paragraph-1' => 'If you have any questions or concerns about this Privacy Policy or our privacy practices, please contact us at <a href="mailto:info.dodl@mul.edu.pk">info.dodl@mul.edu.pk</a>',
                    'points'  => [],
                    'paragraph-2' => ''
                ]
            ]
        ]
    ];

    $rowjson['privacy_policy'] = $privacy_policy;
    $rowjson['success'] = 1;
    $rowjson['MSG'] = "Privacy Policy fetched successfully.";
}
?>