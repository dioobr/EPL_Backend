<?php

class service {
	
	public function get_team(int $id){
		$sr = $this->request('lookupteam', ['team_id' => $id]);
		if($sr['state'] == 'error') return api::state($sr);
		if(!array_key_exists('teams', $sr['data'])) return api::state('error', "Unexpected API response.");
		$team = ikex($sr['data']['teams'], 0);
		if(empty($team) || !is_array($team)) return api::state('error', "Team not found or not available.");
		return api::state('ok', "Team found successfully.", null, $team);
	}
	
	public function get_team_badge(int $id){
		$team = $this->get_team($id);
		if($team['state'] == 'error') return api::state($team);
		$bdge = ikex($team['data'], 'strTeamBadge', "");
		if(empty($bdge)) return api::state('error', "Team badge not available.");
		$badge = [
			'standard' => $bdge,
			'tiny' => $bdge.'/tiny'
		];
		return api::state('ok', "Found successfully.", null, $badge);
	}
	
	public function get_past_events(){
		$sr = $this->request('eventspastleague');
		if($sr['state'] == 'error') return api::state($sr);
		if(!array_key_exists('events', $sr['data'])) return api::state('error', "Unexpected API response.");
			
		$events = [];
		$fks = [
			'dateEvent',
			'strVenue',
			'strHomeTeam',
			'strAwayTeam',
			'intHomeScore',
			'intAwayScore',
			'idHomeTeam',
			'idAwayTeam'
		];
		foreach($sr['data']['events'] as $event){
			$events[] = array_filter($event, function($key) use ($fks){
				return in_array($key, $fks);
			}, ARRAY_FILTER_USE_KEY);			
		}
		
		return api::state('ok', "Processed successfully.", null, $events);
	}
	
	private function request($endpoint, $adpms = []){
		global $config;
		$sdb = $config['sportsdb'];
		if(!array_key_exists($endpoint, $sdb['endpoints'])) return api::state('error', "Invalid endpoint.");
		$ept = $sdb['endpoints'][$endpoint];
		$url = $ept['url'];
		foreach(array_merge($sdb['pms'], $adpms) as $pn => $pv) $url = str_replace('{'.$pn.'}', $pv, $url);
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-type: application/json']);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		$sh = curl_exec($ch);
		$error = curl_errno($ch);
		$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		
		if($error > 0) return api::state('error', "Something is wrong.");
		
		$shr = @json_decode($sh, true);
		if(empty($shr) || !is_array($shr)) return api::state('error', "Something is wrong. Invalid response data.");
		
		return api::state('ok', "Processed successfully.", null, $shr);
	}
}