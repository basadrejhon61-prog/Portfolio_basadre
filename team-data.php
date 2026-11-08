<?php
/**
 * Impormasyon sa matag member.
 * PALIHOG USBA ang mga "placeholder" nga text ngadto sa tinuod nga impormasyon.
 *
 * Key (basadre, cachero, ...) = ang gamiton sa link: ?member=basadre
 * image  = filename sa picture sa imong theme images folder
 * resume = filename sa PDF sa "resume" folder sa theme (pananglitan 'basadre-resume.pdf').
 *          Kung gibiyaan nga '', dili mogawas ang Download Resume button.
 */
if (!function_exists('occ_get_team')) {
function occ_get_team() {
return [

  'basadre' => [
    'name'      => 'Jhon Christian Basadre',
    'color'     => '#fff6f6',
    'image'     => 'basadre.jpg',
    'role'      => 'BSIT Student',
    'email'     => 'email@example.com',
    'phone'     => '+63 9XX XXX XXXX',
    'address'   => 'Opol, Misamis Oriental',
    'about'     => 'Isulat dinhi ang mubo nga paghulagway bahin ni Jhon Christian Basadre, ang iyang mga interes ug tumong.',
    'education' => [
      ['Opol Community College', 'Bachelor of Science in Information Technology', '2022 - Present'],
      ['Senior High School',     'Isulat ang ngalan sa eskwelahan',                  '2020 - 2022'],
    ],
    'skills'    => ['Web Development', 'PHP', 'JavaScript', 'HTML & CSS', 'MySQL'],
    'resume'    => '',
  ],

  'cachero' => [
    'name'      => 'Justine Cachero',
    'color'     => '#eef1f5',
    'image'     => 'profile.jpg',
    'role'      => 'BSIT Student',
    'email'     => 'email@example.com',
    'phone'     => '+63 9XX XXX XXXX',
    'address'   => 'Opol, Misamis Oriental',
    'about'     => 'Isulat dinhi ang mubo nga paghulagway bahin ni Justine Cachero.',
    'education' => [
      ['Opol Community College', 'Bachelor of Science in Information Technology', '2022 - Present'],
    ],
    'skills'    => ['Web Development', 'Database', 'UI Design'],
    'resume'    => '',
  ],

  'campion' => [
    'name'      => 'Jayson Campion',
    'color'     => '#f3f0f8',
    'image'     => 'campion.jpg',
    'role'      => 'BSIT Student',
    'email'     => 'email@example.com',
    'phone'     => '+63 9XX XXX XXXX',
    'address'   => 'Opol, Misamis Oriental',
    'about'     => 'Isulat dinhi ang mubo nga paghulagway bahin ni Jayson Campion.',
    'education' => [
      ['Opol Community College', 'Bachelor of Science in Information Technology', '2022 - Present'],
    ],
    'skills'    => ['Web Development', 'Networking', 'Graphic Design'],
    'resume'    => '',
  ],

  'muego' => [
    'name'      => 'Kim Muego',
    'color'     => '#effff6',
    'image'     => 'profile.jpg',
    'role'      => 'BSIT Student',
    'email'     => 'email@example.com',
    'phone'     => '+63 9XX XXX XXXX',
    'address'   => 'Opol, Misamis Oriental',
    'about'     => 'Isulat dinhi ang mubo nga paghulagway bahin ni Kim Muego.',
    'education' => [
      ['Opol Community College', 'Bachelor of Science in Information Technology', '2022 - Present'],
    ],
    'skills'    => ['Web Development', 'Programming', 'Documentation'],
    'resume'    => '',
  ],

];
}
}