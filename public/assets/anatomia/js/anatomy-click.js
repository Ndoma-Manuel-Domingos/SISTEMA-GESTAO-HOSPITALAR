class AnatomyClick {
    constructor(viewer) {
        this.viewer = viewer;

        this.raycaster = new THREE.Raycaster();

        this.mouse = new THREE.Vector2();

        this.events();
    }

    events() {
        window.addEventListener(
            "click",
            (e) => this.onClick(e)
        );
    }

    onClick(event) {
        this.mouse.x = (event.clientX / window.innerWidth) * 2 - 1;

        this.mouse.y = -(event.clientY / window.innerHeight) * 2 + 1;

        this.raycaster.setFromCamera(
            this.mouse,
            this.viewer.camera
        );

        let intersects = this.raycaster.intersectObjects(
            this.viewer.model.children,
            true
        );

        if (intersects.length) {

            let mesh = intersects[0].object;

            let codigo = mesh.name;

            AnatomyHighlight.highlight(mesh);

            AnatomyApi.buscar(codigo);

            /*let codigo = intersects[0].object.name;

            AnatomyApi.buscar(
                codigo
            );*/
        }
    }
}
