<?php
/* Role info: color, icon, description and tasks for each Capstone role.
   Matching is by keyword, so "Technical Writer" / "Technical Writter" both work. */
function occ_role_meta($role) {
  static $map = null;
  if ($map === null) {
    $map = [
      'programmer' => [
        'label' => 'Programmer', 'color' => '--cobalt', 'kw' => ['programmer', 'developer', 'coder'],
        'icon'  => '<path d="M16 18l6-6-6-6M8 6l-6 6 6 6"/>',
        'desc'  => 'Builds the system: turns the design and requirements into clean, working code.',
        'tasks' => ['Develops the system features and modules', 'Designs and manages the database', 'Tests, debugs and fixes errors', 'Deploys and maintains the system'],
      ],
      'analyst' => [
        'label' => 'System Analyst', 'color' => '--violet', 'kw' => ['analyst'],
        'icon'  => '<path d="M3 3v18h18"/><path d="M7 15l4-4 3 3 5-6"/>',
        'desc'  => 'Studies the problem and defines what the system must do before it is built.',
        'tasks' => ['Gathers and analyzes user requirements', 'Creates DFD, ERD and use case diagrams', 'Designs the system flow and wireframes', 'Checks that the output meets the requirements'],
      ],
      'writer' => [
        'label' => 'Technical Writer', 'color' => '--emerald', 'kw' => ['writ', 'documentation'],
        'icon'  => '<path d="M14 3H6a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"/><path d="M14 3v6h6M8 13h8M8 17h5"/>',
        'desc'  => 'Documents the whole project so it is clear, complete and ready for defense.',
        'tasks' => ['Writes the capstone manuscript (Chapters 1–5)', 'Prepares the user manual and documentation', 'Proofreads and formats all documents', 'Helps prepare the defense presentation'],
      ],
      'manager' => [
        'label' => 'Project Manager', 'color' => '--sun', 'kw' => ['manager', 'leader'],
        'icon'  => '<path d="M4 22V4M4 4h13l-2 4 2 4H4"/>',
        'desc'  => 'Leads the team, plans the schedule and keeps the project on track.',
        'tasks' => ['Plans the timeline and assigns tasks', 'Monitors progress and deadlines', 'Coordinates with the adviser and the panel', 'Keeps the team organized and communicating'],
      ],
    ];
  }
  $r = strtolower((string) $role);
  foreach ($map as $key => $d) {
    foreach ($d['kw'] as $k) {
      if ($r !== '' && strpos($r, $k) !== false) { return $d + ['key' => $key]; }
    }
  }
  return [
    'key' => 'member', 'label' => $role ?: 'Team member', 'color' => '--cobalt',
    'icon' => '<path d="M12 3l2.7 5.6 6.1.9-4.4 4.3 1 6.1L12 17l-5.4 2.9 1-6.1L3.2 9.5l6.1-.9z"/>',
    'desc' => '', 'tasks' => [],
  ];
}

/* Returns the team with the Capstone role assigned to each member.
   Matches by surname first, then falls back to the order in team-data.php
   (1 Programmer, 2 System Analyst, 3 Project Manager, 4 Technical Writer). */
function occ_team_with_roles() {
  $team     = occ_get_team();
  $by_name  = ['basadre' => 'Programmer', 'cachero' => 'System Analyst', 'campion' => 'Project Manager', 'muego' => 'Technical Writer'];
  $by_order = ['Programmer', 'System Analyst', 'Project Manager', 'Technical Writer'];
  $i = 0;
  foreach ($team as $slug => &$m) {
    $name = strtolower($m['name'] ?? '');
    $role = null;
    foreach ($by_name as $k => $r) { if (strpos($name, $k) !== false) { $role = $r; break; } }
    if ($role === null) { $role = $by_order[$i] ?? ($m['role'] ?? ''); }
    $m['role'] = $role;
    $i++;
  }
  unset($m);
  return $team;
}