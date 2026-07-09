<?php
if($data_arr['method_name'] == "get_terms_conditions") {
    $terms = array(
        'heading' => 'These terms should provide clarity and guidance for users engaging with the online distance learning platform while also protecting the website\'s interests and maintaining a positive learning environment.',
        'terms_list' => [
            array('title' => 'Acceptable Use Policy (AUP)'      , 'content' => 'Guidelines outlining acceptable behavior and usage of the website and its services.'),
            array('title' => 'Access'                           , 'content' => 'The ability to log in and utilize the website\'s resources and materials.'),
            array('title' => 'Course Materials'                 , 'content' => 'Educational content, such as lectures, videos, readings, assignments, quizzes, and exams, provided by the website.'),
            array('title' => 'Distance Learning'                , 'content' => 'Education provided remotely, typically through online platforms, enabling students to learn without being physically present in a traditional classroom.'),
            array('title' => 'Enrollment'                       , 'content' => 'The process of registering or signing up for courses on the website.'),
            array('title' => 'Instructor'                       , 'content' => 'A qualified individual responsible for teaching and facilitating learning activities within the courses offered on the website.'),
            array('title' => 'Intellectual Property'            , 'content' => 'Original content, including text, images, videos, and other materials, protected by copyright laws.'),
            array('title' => 'Learner'                          , 'content' => 'An individual enrolled in courses on the website, also referred to as a student or participant.'),
            array('title' => 'Login Credentials'                , 'content' => 'Username and password required to access the website\'s services.'),
            array('title' => 'Online Forum'                     , 'content' => 'A virtual space where learners and instructors can interact, discuss course topics, and ask questions.'),
            array('title' => 'Privacy Policy'                   , 'content' => 'A document outlining how the website collects, uses, and protects users personal information.'),
            array('title' => 'Refund Policy'                    , 'content' => 'Guidelines for requesting refunds for courses or services purchased on the website.'),
            array('title' => 'Syllabus'                         , 'content' => 'An outline or roadmap detailing the topics, schedule, assignments, and expectations for a course.'),
            array('title' => 'Technical Requirements'           , 'content' => 'Minimum hardware, software, and internet connection specifications necessary to access and use the website\'s services effectively.'),
            array('title' => 'Terms of Service'                 , 'content' => 'Legal agreements outlining the terms and conditions of using the website and its services.'),
            array('title' => 'Virtual Classroom'                , 'content' => 'A digital environment where learners attend lectures, participate in discussions, and interact with instructors and peers in real-time.'),
            array('title' => 'Webinar'                          , 'content' => 'An online seminar or workshop conducted via the internet, often including presentations, discussions, and Q&A sessions.'),
            array('title' => 'Withdrawal'                       , 'content' => 'The process of discontinuing enrollment in a course before its completion.'),
            array('title' => 'Zero Tolerance Policy'            , 'content' => 'Policy against any form of misconduct or violation of the website\'s terms, with strict consequences for offenders.'),
            array('title' => '24/7 Support'                     , 'content' => 'Access to customer service or technical assistance at any time, typically through email, chat, or phone.')
        ]
    );

    $rowjson['success']             = 1;
    $rowjson['MSG']                 = "Terms and Conditions fetched successfully";
    $rowjson['terms_conditions']    = $terms;
}
?>