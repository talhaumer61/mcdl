<?php
if($data_arr['method_name'] == "get_privacy_policy") {

    $privacy_policy = [
        [
            'title'   => SITE_NAME.' Privacy Policy',
            'intro'   => SITE_NAME.' is committed to protecting the privacy of our users. This Privacy Policy outlines how we collect, use, disclose, and safeguard your personal information when you visit our website '.SITE_URL.' and use our services.',

            'sections' => [
                [
                    'heading' => 'Information We Collect',
                    'points'  => [
                        'We may collect personal information directly from you when you register, enroll in courses, participate in forums, complete surveys, or interact with our services.',
                        'Types of personal information may include:',
                        '- Contact Information (name, email, address, phone number)',
                        '- Account Credentials (username, password)',
                        '- Payment Information (credit card details)',
                        '- Academic and Professional Information (education, qualifications, employment history)',
                        '- Communication Preferences',
                        'We may also automatically collect device and usage data such as IP address, browser, operating system, referring URLs, and pages viewed.'
                    ]
                ],
                [
                    'heading' => 'How We Use Your Information',
                    'points'  => [
                        'Provide and personalize our services',
                        'Process enrollments and transactions',
                        'Communicate about your account and courses',
                        'Respond to inquiries and provide support',
                        'Analyze and improve service quality',
                        'Send promotional and marketing communications',
                        'Comply with legal obligations'
                    ]
                ],
                [
                    'heading' => 'Information Sharing',
                    'points'  => [
                        'We may share personal information with third-party providers such as payment processors, hosting providers, and marketing platforms.',
                        'We may disclose information in response to legal requests, court orders, or to protect rights, property, or safety.'
                    ]
                ],
                [
                    'heading' => 'Data Security',
                    'points'  => [
                        'We implement reasonable security measures to protect information.',
                        'No method of internet transmission or storage is 100% secure, so absolute security cannot be guaranteed.'
                    ]
                ],
                [
                    'heading' => 'Your Choices',
                    'points'  => [
                        'You may update or correct your account information anytime in account settings.',
                        'You may unsubscribe from marketing communications via the instructions in messages.'
                    ]
                ],
                [
                    'heading' => 'Children\'s Privacy',
                    'points'  => [
                        'Our services are not directed to individuals under 13.',
                        'We do not knowingly collect personal information from children under 13.',
                        'If you become aware a child under 13 has provided us information, please contact us immediately.'
                    ]
                ],
                [
                    'heading' => 'Changes to This Privacy Policy',
                    'points'  => [
                        'We may update this Privacy Policy from time to time.',
                        'Material changes will be posted on the site.',
                        'Continued use of the site after changes means acceptance.'
                    ]
                ],
                [
                    'heading' => 'Contact Us',
                    'points'  => [
                        'If you have any questions or concerns, contact us at info@mul.edu.pk.'
                    ]
                ]
            ]
        ]
    ];

    $rowjson['privacy_policy'] = $privacy_policy;
    $rowjson['success'] = 1;
    $rowjson['MSG'] = "Privacy Policy fetched successfully.";
}
?>