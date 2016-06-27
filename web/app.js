import {Analysis} from 'analysis/analysis';

export class App {
    configureRouter(config, router) {
        config.title = 'Natural Intelligence Genetic Analysis';
        config.map([
            { route: ['', 'welcome'], name: 'welcome', moduleId: './analysis/analysis', nav: true, title: 'Welcome'}
        ]);

        this.router = router;
    }
}
