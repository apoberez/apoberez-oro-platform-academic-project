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
     *      name="orocrm_sales_dashboard_opportunity_by_state_chart",
     *      requirements={"widget"="[\w-]+"}
     * )
     * @Template("OroCRMSalesBundle:Dashboard:opportunityByStatus.html.twig")
     * @param $widget
     * @return array
     */
//    public function opportunityByStatusAction($widget)
//    {
//        $items = $this->getDoctrine()
//            ->getRepository('OroCRMSalesBundle:Opportunity')
//            ->getOpportunitiesByStatus(
//                $this->get('oro_security.acl_helper'),
//                $this->get('oro_dashboard.widget_configs')
//                    ->getWidgetOptions($this->getRequest()->query->get('_widgetId', null))
//                    ->get('dateRange')
//            );
//
//        $widgetAttr = $this->get('oro_dashboard.widget_configs')->getWidgetAttributesForTwig($widget);
//        $widgetAttr['chartView'] = $this->get('oro_chart.view_builder')
//            ->setArrayData($items)
//            ->setOptions(
//                [
//                    'name' => 'bar_chart',
//                    'data_schema' => [
//                        'label' => ['field_name' => 'label'],
//                        'value' => [
//                            'field_name' => 'budget',
//                            'type' => 'currency',
//                            'formatter' => 'formatCurrency'
//                        ]
//                    ],
//                    'settings' => ['xNoTicks' => 2],
//                ]
//            )
//            ->getView();
//
//        return $widgetAttr;
//    }
}
