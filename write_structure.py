import os

def count_files_in_directory(directory):
    """Conta il numero di file in una directory e nelle sue sottodirectory."""
    total_files = 0
    for root, dirs, files in os.walk(directory):
        total_files += len(files)
    return total_files

def write_structure_to_file(base_directory, output_file):
    """Scrive la struttura del progetto Laravel in un file di testo."""
    excluded_dirs = {'vendor', 'node_modules', '.git', 'storage', 'test', 'public', 'licenses', '.venv','pyzk-master'}

    with open(output_file, 'w') as f:
        for root, dirs, files in os.walk(base_directory):
            # Escludere le directory specificate
            if any(excluded in root for excluded in excluded_dirs):
                continue
            
            # Controlla se la directory contiene più di 100 file (incluse le sottodirectory)
            if count_files_in_directory(root) > 100:
                continue
            
            # Scrivi il percorso della directory attuale
            f.write(f'{root}/\n')
            
            # Scrivi il percorso di ogni file all'interno della directory
            for file in files:
                file_path = os.path.join(root, file)
                f.write(f'    {file_path}\n')

if __name__ == "__main__":
    # Directory di base (locale) da cui partire
    base_directory = os.getcwd()  # Cambia questa riga se vuoi specificare un'altra directory di partenza
    output_file = 'laravel_structure.txt'  # Nome del file di output
    write_structure_to_file(base_directory, output_file)
    print(f"Struttura del progetto Laravel scritta in {output_file}")
