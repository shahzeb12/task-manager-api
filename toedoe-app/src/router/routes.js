import TasksPage from '../pages/TasksPage.vue';
import LoginPage from '../pages/LoginPage.vue';
import RegisterPage from '../pages/RegisterPage.vue';
import SummaryPage from '../pages/SummaryPage.vue';
import NotFoundErrorPage from '../pages/errors/NotFoundErrorPage.vue';
import HomePage from '../pages/HomePage.vue';


const routes = [
	{
		path: '/summary',
		component: SummaryPage,
		name: 'summary',
		// meta: {
		// 	auth: true
		// }
	},
	{
		path: '/homepage',
		component: HomePage,
		name: 'homepage',
		// meta: {
		// 	auth: true
		// }
	},
	{
		path: '/tasks',
		component: TasksPage,
		name: 'tasks',
		
	},
	{
		path: '/login',
		component: LoginPage,
		name: 'login',
		
	},
	{
		path: '/register',
		component: RegisterPage,
		name: 'register',
		
	},
	{
		path: '/:notFound(.*)',
		component: NotFoundErrorPage,
		name: 'error.404',
		
	}
];

export default routes;