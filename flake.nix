{
  description = "Budget Tracker for Cloud";

  inputs = {
    nixpkgs.url = "github:nixos/nixpkgs?ref=nixos-unstable";
  };

  outputs = {nixpkgs, ...}: let
    system = "x86_64-linux";
    pkgs = nixpkgs.legacyPackages.${system};
  in {
    devShells.${system}.default = pkgs.mkShellNoCC {
      packages = with pkgs; [
        php
        goose
      ];

      GOOSE_MIGRATION_DIR = "db/migrations"; # don't change
      GOOSE_DRIVER = "mysql"; # don't change
    };
  };
}
