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
    'about'     => 'Isulat dinhi ang mubo nga paghulagway bahin ni Jhon Christian Basadre, ang iyang mga interes ug tumong.',
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
    'about'     => 'Isulat dinhi ang mubo nga paghulagway bahin ni Justine Cachero.',
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
    'about'     => 'Isulat dinhi ang mubo nga paghulagway bahin ni Jayson Campion.',
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
    'about'     => 'Isulat dinhi ang mubo nga paghulagway bahin ni Kim Muego.',
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