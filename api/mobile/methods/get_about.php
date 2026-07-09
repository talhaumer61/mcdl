<?php
if($data_arr['method_name'] == "get_about") {
	$social_links = [];
	foreach (getSocialMediaLinks() as $key => $value) {
		$social_links[$key] = $value['url'];
	}
	$rowjson = [
		 'success'		=> 1
		,'MSG'			=> 'About fetched successfully.'
		,'about'		=> 'The Directorate of Open and Distance Learning (DODL) at Minhaj University Lahore is an innovative initiative designed to make quality education accessible to everyone, regardless of geographical or time constraints. Built on the philosophy of “Learn anytime & anywhere,” DODL bridges the gap between traditional classroom learning and modern digital education by offering flexible, affordable, and learner-centered opportunities. Our programs cover a diverse range of disciplines including Islamic studies, computer sciences, business, social sciences, professional development, and language learning, ensuring that learners of all backgrounds can find courses aligned with their goals. \n Through its robust online platform, DODL combines cutting-edge technology with the academic excellence of Minhaj University’s faculty, delivering interactive lectures, structured course material, and real-time guidance to students. Learners benefit from a personalized pace of study, enabling them to balance education with work, family, or other responsibilities. With thousands of active learners already enrolled and multiple course categories running successfully, DODL has quickly established itself as a hub for flexible, lifelong learning. \n At its core, DODL is not just about providing education, but about empowering individuals to transform their lives through knowledge, skills, and values. Whether you are looking to advance your career, gain new competencies, or pursue higher studies, DODL opens doors to learning without borders, fostering a culture of growth, inclusivity, and continuous improvement.'
		,'social_links' => $social_links
	];
} 