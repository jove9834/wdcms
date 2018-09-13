<?php

function getContentSummary($content) {
    // 优先抓取简历内容含”工作经验“、”工作经历“，含有这2个关键字，则从该关键字开始向下抓取内容200个字，超出省略号显示；
    // $reg = '[(工\s*作\s*经\s*验)|(工\s*作\s*经\s*历)]';
    // $reg = '工\s*?作\s*?经\s*?历';
    $reg = '\s+工\s*作\s*经\s*[历|验][:：\s]+';
    mb_regex_encoding("UTF-8");
    mb_ereg_search_init($content, $reg);
    $r = mb_ereg_search();
//    $a = mb_ereg_search_pos();
//    var_dump($a);
//    exit();

    if(!$r) {
        $pos = 0;
    } else {
        $pos = mb_ereg_search_getpos();
        $r = mb_ereg_search_getregs(); //get first result
        do
        {
            var_dump($r[0]);
            $r = mb_ereg_search_regs();//get next result
        }
        while($r);
    }

//    $pos = strpos($content, '工作经验');
//    $posT = strpos($content, '工作经历');
//    if ($pos !== FALSE && $posT !== FALSE) {
//        $pos = $pos > $posT ? $posT : $pos;
//    } elseif ($posT !== FALSE) {
//        $pos = $posT;
//    } elseif ($pos === FALSE) {
//        $pos = 0;
//    }

//    $content = mb_substr($content, $pos, NULL, 'UTF-8');
    $content = substr($content, $pos);
    // 截取500个字
    if (strlen($content) > 200) {
//        $content = mb_substr($content, 0, 200, 'UTF-8') . "...";
        $content = substr($content, 0, 200) . "...";
    }

    // 手机号、邮箱、QQ号进行屏蔽，变成“*”号
    $patterns = array();
    $patterns[] = '/手机[:：\s]*(\d)+/i';
    $patterns[] = '/Phone[:：\s]*(\d)+/i';
    $patterns[] = '/Mobile[:：\s]*(\d)+/i';
    $patterns[] = '/电话[:：\s]*(\d)+/i';
    $patterns[] = '/Tel[:：\s]*(\d)+/i';
    $patterns[] = '/QQ[:：\s]*(\d)+/i';
    $patterns[] = '/邮箱[:：\s]*(\w+@)/i';
    $patterns[] = '/Email[:：\s]*(\w+@)/i';
    $patterns[] = '/E-mail[:：\s]*(\w+@)/i';

    $replacements = array();
    $replacements[] = '手机: ***';
    $replacements[] = 'Phone: ***';
    $replacements[] = 'Mobile: ***';
    $replacements[] = '电话: ***';
    $replacements[] = 'Tel: ***';
    $replacements[] = 'QQ: ***';
    $replacements[] = '邮箱: ***@';
    $replacements[] = 'Email: ***@';
    $replacements[] = 'E-mail: ***@';
    return preg_replace($patterns, $replacements, $content);
}

$content = "审计-阮礽楷-2006-本科 　　　　　　　　　　个人简历
个人概况
姓名：阮礽楷                                               性别：男                                   出生日期：10/17/1982                          
电话：15880898029                                     邮箱：rengkai2006@163.com
个人能力
-良好的英语听、说、读、写能力；熟练使用 各种办公软件及财务软件；
-四大会计师事务所工作经验，熟悉《企业会计准则》及财务核算；
-企业内部财务管理工作经验，熟悉企业财务管理、内控流程及资本运作；
-已经通过CPA考试中的部分科目
教育背景
2002.9---2006.7        厦门大学                 国际经济和贸易专业               获得经济学学士学位
工 作 经  历：
2012.5 - 至今                         厦门市双丹马实业发展有限公司（燕之屋总部）         财务副总监
组织建立和健全企业各项财务制度，完善企业内控流程；制定融资计划和方案，安排税务筹划；
审核财务报表，进行财务分析并向总经理汇报财务情况；财务部门的日常管理工作
2011.8 - 2012.5                        建德(泉州)工程机械制造有限公司                                投资经理
-负责企业上市财务及内控系统的规划；与各上市中介沟通协调
2007.7 - 2011.8                         安永华明会计师事务所                                                  高级审计员
-参与多个项目的上市审计工作，担任审计项目现场负责人，主要项目有：
     比亚迪股份有限公司（汽车制造）--A股、H股上市审计；
     广东珠江投资股份有限公司（房地产）--A股上市审计；
     广东利海集团有限公司（房地产）--H股上市审计；
     环球市场集团（电子商务）--美国上市审计；
     东莞美康鞋业有限公司（鞋服产业）--年度审计
-作为审计现场负责人，了解企业生产和销售等业务流程、内控体系及财务核算；协调现场审计工作；编制审计报告；并重点关注和具体负责可能存在重大风险的领域和项目。
个人评定
* 有很强的团队精神和责任感， 能够较好的处理人际关系；
* 有较强的学习能力和适应能力，喜欢接受新的挑战；
* 诚实，自律， 对工作认真负责， 做事稳重细心。

Resume
Personal information
Name: Ruan Rengkai                                        Gender: Male
Date of Birth: 10/17/1982                                 
Phone: 15880898029                                        E-mail: rengkai2006@163.com
Qualification & Skills
-Proficient in English listening, speaking, reading and writing
- Skilled with Office Software and Financial Software
-Working experience in EY, be familiar with Accounting Standards 
-Experienced in financial management and internal control
Education
Bachelor of Economic Studies in International economics and business                                              
Xiamen University                                                                                                      July 2002-September 2006
Working experience
Deputy CFO                                                                                                                        May 2012-now
Xiamen Suntama Industry Development Co., Ltd.
- Improving internal control procedures
-Financing and tax arrangements
-Reviewing financial statements, making financial analysis and reporting to the management
-Management of Financial Department
Investment Department Manager                                                                                    Aug 2011 - May 2012
KenTak (Quanzhou) Engineering Mechanical Manufacture Co., Ltd.
- Being responsible for the IPO related work.
Auditor (Senior)                                                                                                                  Jul 2007 - Aug.2011
Ernst & Young (one of the big four accounting firm)
- Being responsible for the IPO audit of many engagements
- Overall understanding the target enterprise for business process, internal control system and accounting treatment, conducting the field work and focusing on the significant subject.
Self-assessment
* Possess excellent inter-personal skills
* Good team-work experience with high achievements
* Self-motivated and highly responsible
 male 0000-00-00 0000-00-00 0000-00-00 4";

$s = getContentSummary($content);
echo nl2br($s);