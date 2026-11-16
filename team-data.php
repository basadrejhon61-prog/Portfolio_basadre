<?php
/**
 * Impormasyon sa matag member.
 * PALIHOG USBA ang mga "placeholder" nga text ngadto sa tinuod nga impormasyon.
 *
 * Key (basadre, cachero, ...) = ang gamiton sa link: ?member=basadre
 * image  = filename sa picture sa imong theme images folder
 * resume = filename sa PDF sa "resume" folder sa theme (pananglitan 'basadre-resume.pdf').
 *          Kung gibiyaan nga '', dili mogawas ang Download Resume button.
 *
 * SOCIAL LINKS (facebook, twitter, instagram, tiktok):
 *          Ibutang ang tibuok link sa profile sa member, pananglitan
 *          'facebook' => 'https://facebook.com/jhon.basadre'
 *          Kung gibiyaan nga '', mo-dim ang icon ug dili ma-click.
 */
if (!function_exists('occ_get_team')) {
function occ_get_team() {
return [

  'basadre' => [
    'name'      => 'Jhon Christian Basadre',
    'color'     => '#fff6f6',
    'image'     => 'basadre.jpg',
    'role'      => 'BSIT Student',
    'email'     => 'basadrejhon62@gmail.com',
    'phone'     => '+63 9082457001',
    'address'   => 'Poblacion, Opol, Misamis Oriental',
    'about'     => 'I am an IT student passionate about programming, web development, and technology. I enjoy creating websites and applications, learning new programming languages, and developing solutions through coding. I am continuously improving my skills and gaining experience as I work toward becoming a professional software developer.',
    'education' => [
      ['Opol Community College', 'Bachelor of Science in Information Technology', '2022 - Present'],
      ['Senior High School',     'Isulat ang ngalan sa eskwelahan',                  '2020 - 2022'],
    ],
    'skills'    => ['Web Development', 'PHP', 'JavaScript', 'HTML & CSS', 'MySQL'],
    'resume'    => '',
    'facebook'  => 'https://www.facebook.com/share/1d4an9edo1/',
    'twitter'   => '',
    'instagram' => 'https://www.instagram.com/james_chris_basadre?obrf=MXA2ZG1iNmU0c29tcw==',
    'tiktok'    => 'https://tiktok.com/@jcbwomanizer09',
  ],

  'cachero' => [
    'name'      => 'Justine Cachero',
    'color'     => '#eef1f5',
    'image'     => 'gwapo.jpg',
    'role'      => 'BSIT Student',
    'email'     => 'janjancachero082705@gmail.com',
    'phone'     => '+63 9701992668',
    'address'   => 'Tingalan, Opol, Misamis Oriental',
    'about'     => 'I am an IT student interested in system analysis, system design, and software development. I enjoy analyzing problems, gathering user requirements, designing system solutions, and understanding how technology can improve business processes. I continuously develop my analytical, technical, and problem-solving skills to become a professional System Analyst.',
    'education' => [
      ['Opol Community College', 'Bachelor of Science in Information Technology', '2022 - Present'],
    ],
    'skills'    => ['Web Development', 'Database', 'UI Design'],
    'resume'    => '',
    'facebook'  => 'https://www.facebook.com/justine.cachero.73',
    'twitter'   => '',
    'instagram' => '',
    'tiktok'    => 'https://tiktok.com/@justinecachero',
  ],

  'campion' => [
    'name'      => 'Jayson Campion',
    'color'     => '#f3f0f8',
    'image'     => 'campion.jpg',
    'role'      => 'BSIT Student',
    'email'     => 'occ.campion.jayson07@gmail.com',
    'phone'     => '+63 9690837369',
    'address'   => 'Sta. Cruz, Tagoloan, Misamis Oriental',
    'about'     => 'I am an IT student with an interest in project management, team collaboration, and software development. I enjoy planning projects, organizing tasks, coordinating with team members, and ensuring that project goals are achieved. I aim to strengthen my leadership, communication, and problem-solving skills while gaining experience in managing technology projects.',
    'education' => [
      ['Opol Community College', 'Bachelor of Science in Information Technology', '2022 - Present'],
    ],
    'skills'    => ['Web Development', 'Networking', 'Graphic Design'],
    'resume'    => '',
    'facebook'  => 'https://www.facebook.com/share/1MYVYUz4qa/',
    'tiktok'    => 'https://tiktok.com/@muzanskie',
  ],

  'muego' => [
    'name'      => 'Kim Muego',
    'color'     => '#effff6',
    'image'     => 'pangit.jpg',
    'role'      => 'BSIT Student',
    'email'     => 'kimkeeshamuego@gmail.com',
    'phone'     => '+63 9758416199',
    'address'   => 'Poblacion,Opol, Misamis Oriental',
    'about'     => 'I am an IT student interested in technical writing and documentation. I enjoy creating clear and organized technical documents, user guides, system manuals, and project documentation. I aim to communicate complex technical information in a simple and understandable way while continuously improving my writing, research, and documentation skills.',
    'education' => [
      ['Opol Community College', 'Bachelor of Science in Information Technology', '2022 - Present'],
    ],
    'skills'    => ['Web Development', 'Programming', 'Documentation'],
    'resume'    => '',
    'facebook'  => 'https://www.facebook.com/share/1PdU8xQ7fF/',
    'twitter'   => '',
    'instagram' => '',
    'tiktok'    => 'https://vt.tiktok.com/ZSpQfCHvV/',
  ],

];
}
}

/**
 * Gmail / mailto links for a team member.
 * Returns null when the email is invalid (so the button is not shown).
 */
if (!function_exists('occ_mail_links')) {
  function occ_mail_links($email, $name = '') {
    $email = trim((string) $email);
    if ($email === '' || !is_email($email)) { return null; }

    $subj = rawurlencode('Hello ' . $name);
    $body = rawurlencode('Hi ' . $name . ",\r\n\r\n");

    return [
      'email'  => $email,
      'gmail'  => 'https://mail.google.com/mail/?view=cm&fs=1&to=' . rawurlencode($email) . '&su=' . $subj . '&body=' . $body,
      'mailto' => 'mailto:' . $email . '?subject=' . $subj . '&body=' . $body,
    ];
  }
}