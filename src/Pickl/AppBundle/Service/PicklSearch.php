<?php

namespace Pickl\AppBundle\Service;

class PicklSearch {

    public function transform($text, $str) {
        return str_replace($text, "<strong>".$text."</strong>", $str);
    }

    public function organizeSearch(array $projects, $arg) {
        $stats = array();

        foreach($projects as $project) {
            $project->setTitle($this->transform($arg, $project->getTitle()));
            $project->setSmallDescription($this->transform($arg, $project->getSmallDescription()));

            $nbTitle = substr_count(strtolower($project->getTitle()),$arg);

            $nbDescription  =    substr_count(strtolower($project->getSmallDescription()),$arg)
                +    substr_count(strtolower($project->getBigDescription()),$arg);

            $hasTag = false;
            foreach($project->getTags() as $tag) {
                if(substr_count(strtolower($tag->getWord()),$arg) >= 1) {
                    $hasTag = true;
                    break;
                }
            }

            $stats[] = array(
                "project" => $project,
                "nbTitle" => $nbTitle,
                "nbDescription" => $nbDescription,
                "hasTag" => $hasTag
            );
        }

        $inTitle = array();
        $inDesc = array();
        $inTag = array();

        foreach($stats as $stat) {
            if($stat['nbTitle'] > 0) {
                $inTitle[] = $stat;
                continue;
            }

            if($stat['nbDescription'] > 0) {
                $inDesc[] = $stat;
                continue;
            }

            $inTag[] = $stat;
        }

        // comment trier un tableau
        function array_sort($array, $on, $order=SORT_ASC)
        {
            $new_array = array();
            $sortable_array = array();

            if (count($array) > 0) {
                foreach ($array as $k => $v) {
                    if (is_array($v)) {
                        foreach ($v as $k2 => $v2) {
                            if ($k2 == $on) {
                                $sortable_array[$k] = $v2;
                            }
                        }
                    } else {
                        $sortable_array[$k] = $v;
                    }
                }

                switch ($order) {
                    case SORT_ASC:
                        asort($sortable_array);
                        break;
                    case SORT_DESC:
                        arsort($sortable_array);
                        break;
                }

                foreach ($sortable_array as $k => $v) {
                    $new_array[$k] = $array[$k];
                }
            }

            return $new_array;
        }

        $projectsArgInTitle = array_sort($inTitle,"nbTitle",SORT_DESC);
        $projectsArgInDesc = array_sort($inDesc,"nbDescription",SORT_DESC);
        $projectsArgInTag = $inTag;

        return array(
            "inTitle" => $projectsArgInTitle,
            "inDesc" => $projectsArgInDesc,
            "inTag" => $projectsArgInTag
        );
    }

}