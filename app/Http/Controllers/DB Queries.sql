ALTER TABLE news_feed CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_bin;
ALTER TABLE comments CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_bin;


$count = DB::table('comments')->where(['feed_id' => $arguments['feed_id']])->get();	
$variable['count'] = count($count);
$data = json_encode($variable);
echo $data;



$current = Carbon::now();
$dt      = Carbon::now();

$dt = $dt->subHours(6);
echo $dt->diffInHours($current);         // -6
echo $current->diffInHours($dt);         // 6

$future = $current->addMonth();
$past   = $current->subMonths(2);
echo $current->diffInDays($future);      // 31
echo $current->diffInDays($past);   


<li><span class="icon flaticon-days">{{ $data['updated_at']->format('l jS ') }}</span></li>
<li><span class="icon flaticon-time">{{ $data['updated_at']->format('h:i:A ') }}</span></li>