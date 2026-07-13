class AnatomyHighlight {

    static selectedObject = null;

    static originalMaterial = null;

    static highlight(mesh) {
        if (!mesh) {
            return;
        }

        if (this.selectedObject) {
            this.selectedObject.material =
                this.originalMaterial;
        }

        this.selectedObject = mesh;

        this.originalMaterial =
            mesh.material.clone();

        mesh.material =
            mesh.material.clone();

        mesh.material.emissive.setHex(
            0x00ff00
        );

        mesh.material.emissiveIntensity = 1;
    }

    static clear() {
        if (this.selectedObject) {
            this.selectedObject.material =
                this.originalMaterial;

            this.selectedObject = null;
        }
    }

}
