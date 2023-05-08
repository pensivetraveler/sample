<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * CodeIgniter Naver Rest Api Class
 *
 * 네이버 연관검색어 추출을 가능하게 하는 클래스
 *
 * @category    Libraries
 * @author      CodexWorld
 * @link        https://www.codexworld.com
 */

class Csv
{
    public function __construct()
    {
        log_message('Debug', 'CSV class is loaded.');
		$this->root_path = $_SERVER['DOCUMENT_ROOT'];
        $this->naver_data_path = '/data/keyword/';
        $this->history_data_path = '/data/history/';
    }

    public function naverKeywordCsvCreate($file_name_real, $keywordList)
    {
        /**
         * 네이버 Keyword List 칼럼 설명
         *
         * [relKeyword] => 김치찌개 // 연관키워드
         * [monthlyPcQcCnt] => 9250 // 월간검색수 PC
         * [monthlyMobileQcCnt] => 54500 // 월간검색수 모바일
         * [monthlyAvePcClkCnt] => 9.7 // 월평균클릭수 PC
         * [monthlyAveMobileClkCnt] => 36 // 월평균클릭수 모바일
         * [monthlyAvePcCtr] => 0.12 // 월평균클릭률 PC
         * [monthlyAveMobileCtr] => 0.07 // 월평균클릭률 모바일
         * [plAvgDepth] => 15  // 월평균 노출 광고 수
         * [compIdx] => 높음  // 경쟁정도
         */

		$root_path = $this->root_path;
		$data_path = $this->naver_data_path;
		$file_root_path = $root_path.$data_path;

		// 만일 동일한 파일명이 존재한다면, 이전에 만든 화일이 있으면 지운다
		if(file_exists($file_root_path.$file_name_real.'.csv')){
			unlink($file_root_path.$file_name_real.'.csv');
		}
		else{
			$fp = fopen($file_root_path.$file_name_real.'.csv',"w") or die("CSV 파일을 생성할 수 없습니다.\n\r서버 설정을 확인해주세요.");
            fputs($fp,"\xEF\xBB\xBF");
		}

        $title = "연관키워드, 월간검색수 PC, 월간검색수 모바일, 월평균클릭수 PC, 월평균클릭수 모바일, 월평균클릭률 PC, 월평균클릭률 모바일, 월평균 노출 광고 수, 경쟁정도";

		// 타이틀
		fwrite($fp, $title);
		//줄 넘김
		$newline = chr(10);
		fwrite($fp, $newline);

		// fputcsv()를 써서 foreach문으로 한줄씩 집어넣는다.
		foreach($keywordList as $line){
			if ( fputcsv($fp, $line) === false ) {
				die("CSV 파일을 쓸 수 없습니다.");
			}
		}

		fclose($fp);

		return $file_name_real;
    }

    public function crawlHistoryCsvCreate($file_name_real_replaced, $craw_history)
    {
		$root_path = $this->root_path;
		$data_path = $this->history_data_path;
		$file_root_path = $root_path.$data_path;

		// 만일 동일한 파일명이 존재한다면, 이전에 만든 화일이 있으면 지운다
		if(file_exists($file_root_path.$file_name_real_replaced.'.csv')){
			unlink($file_root_path.$file_name_real_replaced.'.csv');
		}
		else{
			$fp = fopen($file_root_path.$file_name_real_replaced.'.csv',"w") or die("CSV 파일을 생성할 수 없습니다.\n\r서버 설정을 확인해주세요.");
            fputs($fp,"\xEF\xBB\xBF");
		}

		// 타이틀
		fwrite($fp, "날짜, 시각, 플랫폼, 섹션, 순위");
		//줄 넘김
		$newline = chr(10);
		fwrite($fp, $newline);

		// fputcsv()를 써서 foreach문으로 한줄씩 집어넣는다.
		foreach($craw_history as $line){
			if ( fputcsv($fp, $line) === false ) {
				die("CSV 파일을 쓸 수 없습니다.");
			}
		}

		fclose($fp);

		return $file_name_real_replaced;
    }
}
