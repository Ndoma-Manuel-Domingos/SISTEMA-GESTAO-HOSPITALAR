class AnatomyViewer {

    constructor() {
        this.scene = null;
        this.camera = null;
        this.renderer = null;
        this.model = null;
    }

    init() {
        this.createScene();
        this.loadModel();
        this.animate();
    }

    createScene() {
        this.scene = new THREE.Scene();

        this.camera = new THREE.PerspectiveCamera(75, window.innerWidth / window.innerHeight, 0.1, 1000);

        this.renderer = new THREE.WebGLRenderer({
            antialias: true
        });

        this.renderer.setSize(window.innerWidth, 800);

        document.getElementById("anatomy-viewer").appendChild(this.renderer.domElement);

        this.camera.position.z = 4;
    }

    loadModel() {
        let loader = new THREE.GLTFLoader();

        loader.load(
            "/assets/anatomia/models/corpo_masculino.glb",
            (gltf) => {

                this.model = gltf.scene;

                this.scene.add(
                    this.model
                );

            }
        );
    }

    animate() {
        requestAnimationFrame(
            () => this.animate()
        );

        this.renderer.render(
            this.scene,
            this.camera
        );
    }
}
