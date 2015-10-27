<?php


namespace AP\Bundle\BugTrackerBundle\Controller\Dashboard;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends Controller
{

    /**
     * @Route(
     *      "/opportunity_state/chart/{widget}",
     *      name="bug_tracker.issue_by_status_chart",
     *      requirements={"widget"="[\w-]+"}
     * )
     * @Template("APBugTrackerBundle:Dashboard:issuesByStatus.html.twig")
     * @param $widget
     * @return array
     */
    public function opportunityByStatusAction($widget)
    {
        $items = $this->getDoctrine()
            ->getRepository('APBugTrackerBundle:Issue')
            ->getIssuesCountGroupedByStatus();

        $widgetAttr = $this->get('oro_dashboard.widget_configs')->getWidgetAttributesForTwig($widget);
        $widgetAttr['chartView'] = $this->get('oro_chart.view_builder')
            ->setArrayData($items)
            ->setOptions(
                [
                    'name' => 'bar_chart',
                    'data_schema' => [
                        'label' => ['field_name' => 'label'],
                        'value' => ['field_name' => 'issues_count']
                    ],
                    'settings' => ['xNoTicks' => count($items)],
                ]
            )
            ->getView();

        return $widgetAttr;
    }
}
